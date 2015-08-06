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
use Doctrine\Common\Collections\Collection;
use \NS\ImportBundle\Entity\Import;
use NS\SentinelBundle\Entity\BaseCase;
use \Symfony\Component\HttpFoundation\File\File;
use \Vich\UploaderBundle\Mapping\PropertyMappingFactory;

/**
 * Class ImportResultUpdater
 * @package NS\ImportBundle\Tests\Importer
 */
class ImportResultUpdater
{
    private $factory;

    public function __construct(PropertyMappingFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Import $import
     * @param Result $result
     */
    public function update(Import $import, Result $result, Collection $entities)
    {
        if (!$import->getStartedAt()) {
            $import->setStartedAt($result->getStartTime());
        }

        $import->setPosition($import->getPosition()+$result->getTotalProcessedCount());
        $import->setEndedAt($result->getEndTime());
        $import->incrementProcessedCount($result->getTotalProcessedCount());
        $import->incrementImportedCount($result->getSuccessCount());

        $this->buildLogs($import, $result->getReports());
        $this->buildExceptions($import, $result->getExceptions());
        $this->buildSuccesses($import,$entities);
//        $import->setSuccesses($this->getWriter($import->getClass())->getResults()->toArray());
    }

    /**
     * @param Import $import
     * @param $name
     * @param $property
     * @return File
     */
    public function createNewFile(Import $import, $name, $property) //PropertyMapping $mapping)
    {
        $file    = new File(tempnam(sys_get_temp_dir(), 'warning'));
        $mapping = $this->factory->fromField($import,$property);
        $mapping->setFileName($import, $name);

        // determine the file's directory
        $dir = $mapping->getUploadDir($import);

        $uploadDir = $mapping->getUploadDestination().DIRECTORY_SEPARATOR.$dir;

        return $file->move($uploadDir, $name);
    }

    /**
     * @param \SplObjectStorage $reports
     */
    public function buildLogs(Import $import, \SplObjectStorage $reports)
    {
        $warningFile = ($import->getWarningFile()) ? $import->getWarningFile() : $this->createNewFile($import,'warnings.csv','warningFile');
        $warningCount = $this->buildLog($reports, $warningFile->openFile('a'), ReporterInterface::WARNING);

        $import->setWarningFile($warningFile);
        $import->incrementWarningCount($warningCount);
    }

    /**
     * @param $reports
     * @param \SplFileObject $writer
     * @param $severity
     */
    public function buildLog($reports, \SplFileObject $writer, $severity)
    {
        $row = 0;
        $reports->rewind();
        while ($reports->valid()) {
            $obj = $reports->current();
            foreach ($obj->getMessages($severity) as $message) {
                $item = array('row' => $obj->getRow(), 'message' => $message->getMessage());

                if (1 == $row++) {
                    $headers = array_keys($item);
                    $writer->fputcsv($headers);
                }

                $writer->fputcsv($item);
            }

            $reports->next();
        }

        return $row;
    }

    /**
     * @param array|\SplObjectStorage $exceptions
     */
    public function buildExceptions(Import $import, \SplObjectStorage $exceptions)
    {
        $errorFile = ($import->getErrorFile()) ? $import->getErrorFile() : $this->createNewFile($import,'errors.csv','errorFile');
        $fileWriter = $errorFile->openFile('a');
        $row = 0;
        $exceptions->rewind();
        while ($exceptions->valid()) {
            $object = $exceptions->current(); // similar to current($s)
            $row = $exceptions->offsetGet($object);

            $item = array('row' => $row,'column' => $object->getMessage(),'message' => ($object->getPrevious()) ? $object->getPrevious()->getMessage() : null);
            if (1 == $row++) {
                $headers = array_keys($item);
                $fileWriter->fputcsv($headers);
            }

            $fileWriter->fputcsv($item);

            $exceptions->next();
        }

        $import->setErrorFile($errorFile);
    }

    /**
     * @param Import $result
     * @param Collection $entities
     */
    public function buildSuccesses(Import $import, Collection $entities)
    {
        $successFile = ($import->getSuccessFile()) ? $import->getSuccessFile() : $this->createNewFile($import,'successes.csv','successFile');
        $fileWriter = $successFile->openFile('a');

        $row = 0;
        foreach($entities as $entity) {
            $item = array(
                'id' => $entity->getId(),
                'caseId' => $entity->getCaseId(),
                'site' => $entity->getSite()->getCode(),
                'siteName' => $entity->getSite()->getName(),
            );

            $this->addLabSuccess($entity, $item);
            if (1 == $row++) {
                $headers = array_keys($item);
                $fileWriter->fputcsv($headers);
            }
            $fileWriter->fputcsv($item);
        }
    }


    /**
     * @param $entity
     * @param array $item
     */
    public function addLabSuccess(BaseCase $entity, array &$item)
    {
        if($entity->hasReferenceLab()){
            $item['referenceLab.id'] = $entity->getReferenceLab()->getLabId();
        }
        if($entity->hasNationalLab()){
            $item['nationalLab.id'] = $entity->getNationalLab()->getLabId();
        }
    }
}