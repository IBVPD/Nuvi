<?php

namespace NS\ImportBundle\Importer;

use \Ddeboer\DataImport\ReporterInterface;
use \Ddeboer\DataImport\Result;
use \Doctrine\ORM\Proxy\Proxy;
use \NS\ImportBundle\Entity\Import;
use \NS\SentinelBundle\Entity\BaseCase;

/**
 * Class ImportResultUpdater
 * @package NS\ImportBundle\Tests\Importer
 */
class ImportResultUpdater
{
    private $messageFile;

    /**
     * @param Import $import
     * @param Result $result
     * @param array $entities
     */
    public function update(Import $import, Result $result, $entities)
    {
        if (!$import->getStartedAt()) {
            $import->setStartedAt($result->getStartTime());
        }

        $writeHeaders = ($import->getPosition() == 0);

        $import->setPosition($import->getPosition() + $result->getTotalProcessedCount());
        $import->setSkippedCount($result->getSkippedCount());
        $import->setEndedAt($result->getEndTime());
        $import->incrementProcessedCount($result->getTotalProcessedCount());
        $import->incrementImportedCount($result->getSuccessCount());

        $mFile = $import->getMessageFile();
        $this->messageFile = $mFile->openFile('a+');

        $this->buildWarnings($import, $result->getReports(), $writeHeaders);
        $this->buildExceptions($import, $result->getExceptions(), $writeHeaders);
        $this->buildErrors($import, $result->getReports(), ($writeHeaders && $result->getExceptions()->count() > 0));
        $this->buildSuccesses($import, $entities, $writeHeaders);
    }

    /**
     * @param Import $import
     * @param \SplObjectStorage $reports
     * @param boolean $writeHeader
     *
     * todo This really should handle everything the reports have (errors and any other messages of any other severity)
     *      It would alleviate the call to buildErrors above.
     */
    public function buildWarnings(Import $import, \SplObjectStorage $reports, $writeHeader)
    {
        $warningFile = $import->getWarningFile();
        $warningCount = $this->updateWarning($reports, $warningFile->openFile('a'), ReporterInterface::WARNING, $writeHeader);
        $import->incrementWarningCount($warningCount);
    }

    /**
     * @param \SplObjectStorage $reports
     * @param \SplFileObject $writer
     * @param int $severity
     * @param boolean $writeHeader
     * @return int
     */
    public function updateWarning(\SplObjectStorage $reports, \SplFileObject $writer, $severity = null, $writeHeader = false)
    {
        $rowCount = 0;
        $first = true;
        $reports->rewind();
        while ($reports->valid()) {
            $obj = $reports->current();
            foreach ($obj->getMessages($severity) as $message) {
                $item = ['row' => $obj->getRow(), 'message' => $message->getMessage(),'column'=>($message->getColumn()?$message->getColumn():'unknown')];

                if ($writeHeader && $first) {
                    $headers = array_keys($item);
                    $writer->fputcsv($headers);
                    $first = false;
                }

                $writer->fputcsv($item);
                $this->messageFile->fputcsv($item);
                $rowCount++;
            }

            $reports->next();
        }

        return $rowCount;
    }

    /**
     * @param Import $import
     * @param \SplObjectStorage $exceptions
     * @param boolean $writeHeader
     */
    public function buildExceptions(Import $import, \SplObjectStorage $exceptions, $writeHeader)
    {
        $first      = true;
        $errorFile  = $import->getErrorFile();
        $fileWriter = $errorFile->openFile('a');

        $exceptions->rewind();
        while ($exceptions->valid()) {
            $object = $exceptions->current(); // similar to current($s)
            $row = $exceptions->offsetGet($object);

            $item = ['row' => $row, 'column' => $object->getMessage(), 'message' => ($object->getPrevious()) ? $object->getPrevious()->getMessage() : null];
            if ($writeHeader && $first) {
                $headers = array_keys($item);
                $fileWriter->fputcsv($headers);
                $first = false;
            }

            $fileWriter->fputcsv($item);
            $this->messageFile->fputcsv($item);

            $exceptions->next();
        }
    }

    public function buildErrors(Import $import, \SplObjectStorage $reports, $writeHeader)
    {
        $first = true;
        $errorFile  = $import->getErrorFile();
        $writer = $errorFile->openFile('a');

        $reports->rewind();
        while ($reports->valid()) {
            $obj = $reports->current();
            foreach ($obj->getMessages(ReporterInterface::ERROR) as $message) {
                $item = ['row' => $obj->getRow(), 'message' => $message->getMessage(),'column'=>($message->getColumn()?$message->getColumn():'unknown')];

                if ($writeHeader && $first) {
                    $headers = array_keys($item);
                    $writer->fputcsv($headers);
                    $first = false;
                }

                $writer->fputcsv($item);
                $this->messageFile->fputcsv($item);
            }

            $reports->next();
        }
    }

    /**
     * @param Import $import
     * @param array $entities
     * @param boolean $writeHeaders
     * @internal param Import $result
     */
    public function buildSuccesses(Import $import, $entities, $writeHeaders)
    {
        $successFile = $import->getSuccessFile();
        $fileWriter = $successFile->openFile('a');
        $headers = ['id','case_id','country','site','siteName'];
        $first = false;
        foreach ($entities as $entity) {
            $item = [
                'id' => $entity->getId(),
                'case_id' => $entity->getCaseId(),
                'country' => $entity->getCountry()->getCode(),
            ];

            if ($entity->getSite()) {
                $item['site'] = $entity->getSite()->getCode();//:'Not linked to site',
                $item['siteName'] = ($entity->getSite() instanceof Proxy) ? null : $entity->getSite()->getName();
            } else {
                $item['site'] = 'XXX';
                $item['siteName'] = 'NOT LINKED!!';
            }

            $this->addLabSuccess($entity, $item);
            if ($writeHeaders && $first) {
                $fileWriter->fputcsv($headers);
                $first = false;
            }

            $fileWriter->fputcsv($item);
            $this->messageFile->fputcsv($item);
        }
    }


    /**
     * @param $entity
     * @param array $item
     */
    public function addLabSuccess(BaseCase $entity, array &$item)
    {
        if ($entity->hasReferenceLab()) {
            $item['referenceLab.id'] = $entity->getReferenceLab()->getLabId();
        }

        if ($entity->hasNationalLab()) {
            $item['nationalLab.id'] = $entity->getNationalLab()->getLabId();
        }
    }
}
