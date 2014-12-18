<?php

namespace NS\SentinelBundle\Command;

use \NS\SentinelBundle\Entity\RotaVirus;
use \NS\SentinelBundle\Form\Types\Diagnosis;
use \NS\SentinelBundle\Form\Types\Gender;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of ImportCommand
 *
 * @author gnat
 */
class CreateRotaCasesCommand extends BaseCreateCaseCommand
{
    protected function configure()
    {
        $this
            ->setName('nssentinel:rota:create:cases')
            ->setDescription('Create RotaVirus cases')
            ->addArgument('codes', null, InputArgument::REQUIRED)
            ->addOption('casecount', null, InputOption::VALUE_OPTIONAL, null, 2700)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '768M');

        $codes       = explode(",", str_replace(' ', '', $input->getArgument('codes')));
        $caseCount   = $input->getOption('casecount');
        $entityMgr   = $this->getContainer()->get('ns.model_manager');
        $sites       = $entityMgr->getRepository('NSSentinelBundle:Site')->getChainByCode($codes);
        $male        = new Gender(Gender::MALE);
        $fmale       = new Gender(Gender::FEMALE);
        $diagnosis[] = new Diagnosis(Diagnosis::SUSPECTED_MENINGITIS);
        $diagnosis[] = new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA);
        $diagnosis[] = new Diagnosis(Diagnosis::SUSPECTED_SEPSIS);
        $diagnosis[] = new Diagnosis(Diagnosis::OTHER);

        for ($x = 0; $x < $caseCount; $x++)
        {
            $dob     = $this->getRandomDate();
            $siteKey = array_rand($sites);

            $case = new RotaVirus();
            $case->setDob($dob);
            $case->setAdmDate($this->getRandomDate(null, $dob));
            $case->setSite($sites[$siteKey]);
            $case->setCaseId($this->getCaseId($sites[$siteKey]));
            $case->setGender(($x % 7) ? $fmale : $male);

            $entityMgr->persist($case);

            if ($x % 100 == 0)
                $entityMgr->flush();
        }

        $entityMgr->flush();
        $output->writeln(sprintf("Create %d rota cases", $caseCount));
    }
}
