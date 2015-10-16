<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 05/08/15
 * Time: 2:01 PM
 */

namespace NS\ImportBundle\Importer;

use \Ddeboer\DataImport\ReporterInterface;
use \Ddeboer\DataImport\Result;
use \Doctrine\Common\Collections\Collection;
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
     */
    public function update(Import $import, Result $result, Collection $entities)
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

        $this->buildLogs($import, $result->getReports(), $writeHeaders);
        $this->buildExceptions($import, $result->getExceptions(), $writeHeaders);
        $this->buildSuccesses($import, $entities, $writeHeaders);
    }

    /**
     * @param Import $import
     * @param \SplObjectStorage $reports
     * @param boolean $writeHeader
     */
    public function buildLogs(Import $import, \SplObjectStorage $reports, $writeHeader)
    {
        $warningFile = $import->getWarningFile();
        $warningCount = $this->buildLog($reports, $warningFile->openFile('a'), ReporterInterface::WARNING, $writeHeader);
        $import->incrementWarningCount($warningCount);
    }

    /**
     * @param \SplObjectStorage $reports
     * @param \SplFileObject $writer
     * @param int $severity
     * @param boolean $writeHeader
     * @return int
     */
    public function buildLog(\SplObjectStorage $reports, \SplFileObject $writer, $severity = null, $writeHeader = false)
    {
        $rowCount = 0;
        $first = true;
        $reports->rewind();
        while ($reports->valid()) {
            $obj = $reports->current();
            foreach ($obj->getMessages($severity) as $message) {
                $item = array('row' => $obj->getRow(), 'message' => $message->getMessage(),'column'=>($message->getColumn()?$message->getColumn():'unknown'));

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

            $item = array('row' => $row, 'column' => $object->getMessage(), 'message' => ($object->getPrevious()) ? $object->getPrevious()->getMessage() : null);
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

    /**
     * @param Import $import
     * @param Collection $entities
     * @param boolean $writeHeaders
     * @internal param Import $result
     */
    public function buildSuccesses(Import $import, Collection $entities, $writeHeaders)
    {
        $successFile = $import->getSuccessFile();
        $fileWriter = $successFile->openFile('a');

        $first = false;
        foreach ($entities as $entity) {
            $item = array(
                'id' => $entity->getId(),
                'caseId' => $entity->getCaseId(),
                'site' => $entity->getSite()->getCode(),
                'siteName' => $entity->getSite()->getName(),
            );

            $this->addLabSuccess($entity, $item);
            if ($writeHeaders && $first) {
                $headers = array_keys($item);
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
