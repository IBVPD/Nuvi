<?php

namespace NS\SentinelBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\Listener\MeningitisListener;
use NS\SentinelBundle\Entity\Listener\PneumoniaListener;
use NS\SentinelBundle\Entity\Listener\RotaVirusListener;
use NS\SentinelBundle\Entity\Meningitis\Meningitis;
use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
use NS\SentinelBundle\Entity\RotaVirus;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateAgeDistributionCommand extends ContainerAwareCommand
{
    protected function configure(): void
    {
        $this
            ->setName('nssentinel:update:age_distribution')
            ->setDefinition([
                new InputArgument('type', InputArgument::REQUIRED, 'Meningitis (m), Pneumonia (p), RotaVirus (r)'),
                new InputArgument('page', InputArgument::REQUIRED, 'Page of results to process'),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');
        switch ($type) {
            case 'm':
                $caseClass = Meningitis::class;
                $listener  = new MeningitisListener();
                break;
            case 'p':
                $caseClass = Pneumonia::class;
                $listener  = new PneumoniaListener();
                break;
            case 'r':
                $caseClass = RotaVirus::class;
                $listener  = new RotaVirusListener();
                break;
            default:
                throw new RuntimeException('Unable to determine case type');
        }

        /** @var EntityManagerInterface $em */
        $em    = $this->getContainer()->get('doctrine.orm.entity_manager');
        $pager = $this->getContainer()->get('knp_paginator');
        /** @var EntityRepository $repository */
        $repository = $em->getRepository($caseClass);
        $page       = $input->getArgument('page');
        $pagination = $pager->paginate($repository->createQueryBuilder($type), $page, 750);

        $output->writeln("Processing Page: $page of {$pagination->getPageCount()}");
        /** @var BaseCase $case */
        foreach ($pagination as $case) {
            $listener->calculateAge($case);
            $em->persist($case);
        }

        $em->flush();

        $output->writeln('Done. ');
    }
}
