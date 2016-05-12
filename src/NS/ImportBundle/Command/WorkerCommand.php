<?php

namespace NS\ImportBundle\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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
                    new InputOption('batch-size', 'b', InputOption::VALUE_REQUIRED, 'Set the number of rows to process at a time', 250)
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
        $entityMgr  = $container->get('doctrine.orm.entity_manager');

        $pheanstalk
            ->watch('import')
            ->ignore('default');

        $job = $pheanstalk->reserve(0);

        if ($job) {
            $output->writeln(sprintf("Processing Job %d, ImportId: %d", $job->getId(), $job->getData()));

            $import = $this->setupUser($job->getData(), $entityMgr);

            if($import)
            {
                try {
                    if (!$worker->consume($import, $batchSize)) {
                        $pheanstalk->release($job);
                        $output->writeln("Processed and returned for additional processing");
                    } else {
                        $output->writeln("Import complete - Job removed");
                        $pheanstalk->delete($job);
                    }
                } catch (\Exception $exception) {
                    $pheanstalk->bury($job);

                    $errOutput->writeln('Error processing job');
                    if ($entityMgr->isOpen()) {
                        $entityMgr->getRepository('NSImportBundle:Import')->setImportException($job->getData(), $exception);
                    }

                    $errOutput->writeln('Exception: '.$exception->getMessage());
                    foreach ($exception->getTrace() as $index => $trace) {
                        $errOutput->writeln(sprintf(sprintf("%d: %s::%s on line %d\n", $index, isset($trace['class'])?$trace['class']:'Unknown', isset($trace['function'])?$trace['function']:'Unknown', isset($trace['line'])?$trace['line']:-1)));
                    }
                }
            } else {
                $errOutput->writeln(sprintf('Import: %d doesn\'t exist',$job->getData()));
            }
        }
    }

    protected function setupUser($importId, ObjectManager $entityMgr)
    {
        $import = $entityMgr->getRepository('NSImportBundle:Import')->find($importId);
        if($import) {
            $user = $import->getUser();
            $user->getAcls();
            $token = new UsernamePasswordToken($user, '', 'main_app', $user->getRoles());
            $this->getContainer()->get('security.token_storage')->setToken($token);
            $this->getContainer()->get('ns_sentinel.loggable_listener')->setUsername($user->getUsername());
        }

        return $import;
    }
}
