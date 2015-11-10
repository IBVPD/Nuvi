<?php

namespace NS\ImportBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

class WorkerCommand extends ContainerAwareCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('nsimport:run-batch')
            ->setDescription('Check and run beanstalk batches')
            ->setDefinition(array(
                    new InputOption('batch-size','b',InputOption::VALUE_REQUIRED,'Set the number of rows to process at a time',250)
                )

            );
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $batchSize = $input->getOption('batch-size');
        $output->writeln('Checking for jobs');
        $errOutput = $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output;

        $container  = $this->getContainer();
        $pheanstalk = $container->get("leezy.pheanstalk");
        $worker     = $container->get('ns_import.batch_worker');
        $pheanstalk
            ->watch('import')
            ->ignore('default');

        $job = $pheanstalk->reserve(0);

        if($job) {
            $output->writeln(sprintf("Processing Job %d, ImportId: %d", $job->getId(), $job->getData()));

            try {
                if (!$worker->consume($job->getData(),$batchSize)) {
                    $pheanstalk->release($job);
                    $output->writeln("Processed and returned for additional processing");
                } else {
                    $output->writeln("Import complete - Job removed");
                    $pheanstalk->delete($job);
                }
            } catch(\Exception $exception) {
                $pheanstalk->bury($job);

                $errOutput->writeln('Exception: '.$exception->getMessage());
                foreach($exception->getTrace() as $index => $trace) {
                    $errOutput->writeln(sprintf('%d: %s::%s on line %d',$index,$trace['class'],$trace['function'],$trace['line']));
                }
            }
        }
    }
}
