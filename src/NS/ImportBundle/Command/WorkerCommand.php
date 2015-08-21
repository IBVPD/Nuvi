<?php

namespace NS\ImportBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WorkerCommand extends ContainerAwareCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('nsimport:run-batch')
            ->setDescription('Check and run beanstalk batches');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Checking for jobs');

        $container  = $this->getContainer();
        $pheanstalk = $container->get("leezy.pheanstalk");
        $worker     = $container->get('ns_import.batch_worker');

        while ($job = $pheanstalk->reserveFromTube('import')) {
            $output->writeln(sprintf("Processing Job %d, ImportId: %d", $job->getId(), $job->getData()));

            if (!$worker->consume($job->getData())) {
                $output->writeln("Processed and returned for additional processing");
                $pheanstalk->put($job->getData());
            } else {
                $output->writeln("Import complete");
            }
        }
    }
}