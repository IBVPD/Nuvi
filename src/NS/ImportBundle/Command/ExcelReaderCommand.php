<?php

namespace NS\ImportBundle\Command;

use NS\ImportBundle\Reader\ExcelReader;
use SplFileObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of CsvReaderCommand
 *
 * @author gnat
 */
class ExcelReaderCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('nsimport:readexcel')
            ->setDescription('Reads and analysis a CSV')
            ->addArgument('file', InputArgument::REQUIRED)
            ->addOption('headerRole','r',InputOption::VALUE_REQUIRED,'What header row to read from',0)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath  = $input->getArgument('file');
        $file      = new SplFileObject($filePath);
        $csvReader = new ExcelReader($file);
        $csvReader->setHeaderRowNumber($input->getOption('headerRole')-1);

        $output->writeln("File Columns");
        foreach ($csvReader->getColumnHeaders() as $index => $column) {
            $output->writeln(sprintf("%d => '%s'", $index, $column));
        }

        $output->writeln(sprintf("%s has %d rows", $filePath, $csvReader->count()));
    }
}
