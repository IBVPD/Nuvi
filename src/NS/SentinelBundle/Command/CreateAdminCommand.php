<?php

namespace NS\SentinelBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use NS\SentinelBundle\Entity\User;

/**
 * Description of CreateAdminCommand
 *
 * @author gnat
 * @codeCoverageIgnore
 */
class CreateAdminCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('nssentinel:create:admin')
            ->setDescription('Create Administrative User')
            ->addArgument('email',    InputArgument::REQUIRED)
            ->addArgument('name',     InputArgument::REQUIRED)
            ->addArgument('password', InputArgument::REQUIRED)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pword = $input->getArgument('password');
        if (strlen($pword) < 6) {
            $output->writeln("Password must be a minimum of 6 characters");
            return;
        }

        $factory = $this->getContainer()->get('security.encoder_factory');

        $user = new User();
        $user->setName($input->getArgument('name'));
        $user->setEmail($input->getArgument('email'));
        $user->setActive(true);
        $user->setAdmin(true);

        $encoder = $factory->getEncoder($user);
        $user->setPassword($encoder->encodePassword($pword, $user->getSalt()));
        
        try {
            $entityMgr = $this->getContainer()->get('doctrine.orm.entity_manager');
            $entityMgr->persist($user);
            $entityMgr->flush();

            $output->writeln("User Created Successfully");
        } catch (\RuntimeException $e) {
            $output->writeln("Unable to add user");
            $output->writeln($e->getMessage());
        }
    }
}
