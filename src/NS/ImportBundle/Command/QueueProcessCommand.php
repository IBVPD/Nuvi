<?php

namespace NS\ImportBundle\Command;

use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of QueueProcessCommand
 *
 * @author gnat
 */
class QueueProcessCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('nsimport:process:queue')
            ->setDescription('Process the import queue')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $processor = $this->getContainer()->get('ns_import.services.queue_processor');
        $processor->process();
    }
}