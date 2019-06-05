<?php

namespace NS\SentinelBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NotifyChangeCommand extends ContainerAwareCommand
{
    protected function configure(): void
    {
        $this
            ->setName('nssentinel:changes:notify')
            ->setDescription('Notify of changes')
            ->setDefinition([
                new InputArgument('email', InputArgument::REQUIRED, 'Email address'),
                new InputArgument('name', InputArgument::REQUIRED, 'Destination name'),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ret = $this->getContainer()->get('ns_sentinel.detect_changes')->sendChanges($input->getArgument('name'), $input->getArgument('email'));
        $output->writeln($ret ? 'Changes sent' : 'No changes - or errors');
    }
}
