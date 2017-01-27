<?php

namespace NS\SentinelBundle\Command;

use Ddeboer\DataImport\Step\ValueConverterStep;
use Ddeboer\DataImport\Workflow\StepAggregator;
use NS\ImportBundle\Reader\ExcelReader;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use NS\SentinelBundle\Converter\ArrayChoiceConverter;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class UpdateSiteCommand
 * @package NS\SentinelBundle\Command
 * @codeCoverageIgnore
 */
class UpdateSiteCommand extends ContainerAwareCommand
{
    private $surveillance;
    private $support;
    private $countries = [];
    private $countryRepo = null;

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('nssentinel:import:update-sites')
            ->setDescription('Update the system site list')
            ->setDefinition([
                new InputArgument('file', InputArgument::REQUIRED),
            ]);
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityMgr = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->countryRepo = $entityMgr->getRepository('NSSentinelBundle:Country');
        $this->surveillance = new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\SurveillanceConducted');
        $this->support = new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\IntenseSupport');

        $file = $input->getArgument('file');
        if (!is_file($file)) {
            $output->writeln(sprintf('Cannot open file %s', $file));
        }

        $fileObj = new File($file);
        $reader = new ExcelReader($fileObj->openFile());
        $reader->setColumnHeaders([
            'country',
            'code',
            'name',
            'surveillanceConducted',
            'ibdTier',
            'ibdIntenseSupport'
        ]);

        $writer = new DoctrineWriter($entityMgr, 'NS\SentinelBundle\Entity\Site', ['code']);
        $writer->setTruncate(false);
        $worker = new StepAggregator($reader);
        $worker->addWriter($writer);


        $converterStep = new ValueConverterStep();
        $converterStep->add('[surveillanceConducted]', [$this, 'convertSurveillance']);
        $converterStep->add('[ibdIntenseSupport]', [$this, 'convertSupport']);
        $converterStep->add('[country]', [$this, 'convertCountry']);
        $worker->addStep($converterStep);

        try {
            $result = $worker->process();
            $output->writeln(sprintf('Processed %d records', $result->getSuccessCount()));
        } catch (\Exception $exception) {
            $output->writeln('Exception: '.$exception->getMessage());
            foreach ($exception->getTrace() as $index => $trace) {
                $output->writeln(sprintf('%d: %s::%s on line %d', $index, $trace['class'], $trace['function'], $trace['line']));
            }
        }
    }

    public function convertSurveillance($value)
    {
        return $this->surveillance->__invoke($value);
    }

    public function convertSupport($value)
    {
        return $this->support->__invoke($value);
    }

    public function convertCountry($countryCode)
    {
        if (isset($this->countries[$countryCode])) {
            return $this->countries[$countryCode];
        }

        $country = $this->countryRepo->findOneBy(['code'=>$countryCode]);
        if ($country) {
            $this->countries[$countryCode] = $country;
        }

        return $country;
    }
}
