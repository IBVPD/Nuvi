<?php

namespace NS\ImportBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessImportCommand extends ContainerAwareCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('nsimport:process-import')
            ->setDescription('Processes one batch of a given import')
            ->addOption('batch-size','b',InputOption::VALUE_OPTIONAL,'Batch size to process',400)
            ->addArgument('id', InputArgument::REQUIRED);
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $batchSize = $input->getOption('batch-size');

        $worker = $this->get('ns_import.batch_worker');
        $worker->consume($input->getArgument('id'),$batchSize);

        $output->writeln(sprintf("Processed %d lines",$batchSize));
    }

}