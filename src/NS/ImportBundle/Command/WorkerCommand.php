<?php

namespace NS\ImportBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use NS\ImportBundle\Entity\Import;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use TypeError;

class WorkerCommand extends ContainerAwareCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('nsimport:run-batch')
            ->setDescription('Check and run beanstalk batches')
            ->setDefinition([
                    new InputOption('batch-size', 'b', InputOption::VALUE_REQUIRED, 'Set the number of rows to process at a time', 250),
                ]

            );
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $batchSize = $input->getOption('batch-size');
        if ($input->getOption('verbose')) {
            $output->writeln('Checking for jobs');
        }

        $errOutput = $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output;

        $container = $this->getContainer();
        $worker    = $container->get('ns_import.batch_worker');
        $entityMgr = $container->get('doctrine.orm.entity_manager');

        $import = $entityMgr->getRepository(Import::class)->getNewOrRunning();

        if ($import) {
            $output->writeln(sprintf('Processing ImportId: %d', $import->getId()));

            $this->setupUser($import);

            try {
                if (!$worker->consume($import, $batchSize)) {
                    $output->writeln('Processed and returned for additional processing');
                } else {
                    $output->writeln('Import complete - Job removed');
                }
            } // PHP7 only!
            catch (TypeError $exception) {
                $this->handleError($errOutput, $entityMgr, $import, $exception);
            } catch (Exception $exception) {
                $this->handleError($errOutput, $entityMgr, $import, $exception);
            }
        } elseif ($input->getOption('verbose')) {
            $output->writeln('No job?');
        }
    }

    private function handleError(OutputInterface $errOutput, EntityManagerInterface $entityMgr, Import $import, $exception): void
    {
        $errOutput->writeln('Error processing job');
        if ($entityMgr->isOpen()) {
            $entityMgr->getRepository('NSImportBundle:Import')->setImportException($import, $exception);
        } else {
            $errOutput->writeln('Entity Manager Closed - Unable to save error message');
        }

        $errOutput->writeln('Exception: ' . $exception->getMessage());
        foreach ($exception->getTrace() as $index => $trace) {
            $errOutput->writeln(sprintf(sprintf("%d: %s::%s on line %d\n", $index, $trace['class'] ?? 'Unknown', $trace['function'] ?? 'Unknown', $trace['line'] ?? -1)));
        }
    }

    protected function setupUser(Import $import): void
    {
        $user = $import->getUser();
        $user->getAcls();
        $token = new UsernamePasswordToken($user, '', 'main_app', $user->getRoles());
        $this->getContainer()->get('security.token_storage')->setToken($token);
        $this->getContainer()->get('ns_sentinel.loggable_listener')->setUsername($user->getUsername());
    }
}
