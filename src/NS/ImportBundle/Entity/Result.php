<?php

namespace NS\ImportBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \NS\ImportBundle\Writer\Result as WorkflowResult;
use \NS\SentinelBundle\Entity\User;
use \Symfony\Component\HttpFoundation\File\File;
use \Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Description of Result
 *
 * @author gnat
 *
 * @ORM\Entity(repositoryClass="NS\ImportBundle\Repository\ResultRepository")
 * @ORM\Table(name="import_results")
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Result
{

    /**
     * @var integer $id
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Map $map
     * @ORM\ManyToOne(targetEntity="Map",inversedBy="results")
     */
    private $map;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="import_file", fileNameProperty="filename")
     *
     * @var File $imageFile
     */
    private $importFile;

    /**
     * @var \DateTime $createdAt
     * @ORM\Column(name="createdAt",type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime $importStartedAt
     * @ORM\Column(name="importStartedAt",type="datetime")
     */
    private $importStartedAt;

    /**
     * @var \DateTime $importEndedAt
     * @ORM\Column(name="importEndedAt",type="datetime")
     */
    private $importEndedAt;

    /**
     * @var string $filename
     * @ORM\Column(name="filename",type="string")
     */
    private $filename;

    /**
     * @var integer $totalRows
     * @ORM\Column(name="totalRows",type="integer")
     */
    private $totalRowCount;

    /**
     * @var integer $totalCount
     * @ORM\Column(name="totalCount",type="integer")
     */
    private $totalCount;

    /**
     * @var integer $successCount
     * @ORM\Column(name="successCount",type="integer")
     */
    private $successCount;

    /**
     * @var array $mapName
     * @ORM\Column(name="mapName",type="array")
     */
    private $mapName;

    /**
     * @var array $warnings
     * @ORM\Column(name="warnings",type="array")
     */
    private $warnings;

    /**
     * @var array $successes
     * @ORM\Column(name="successes",type="array")
     */
    private $successes = array();

    /**
     * @var array $errors
     * @ORM\Column(name="errors",type="array")
     */
    private $errors = array();

    /**
     * @var array $duplicates
     * @ORM\Column(name="duplicates",type="array")
     */
    private $duplicates;

    /**
     * @var NS\SentinelBundle\Entity\User $user
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\User")
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     *
     * @param WorkflowResult $result
     */
    public function update(WorkflowResult $result)
    {
        $this->buildSuccesses($result);
        $this->buildExceptions($result->getExceptions());

        $this->totalCount    = $result->getTotalProcessedCount();
        $this->successCount  = $result->getSuccessCount();
        if(!$this->importStartedAt){
            $this->importStartedAt = $result->getStartTime();
        }
        $this->importEndedAt = $result->getEndtime();
        $this->duplicates    = $result->getDuplicates()->toArray();
        $this->totalRowCount = $result->getTotalRows();
    }

    /**
     *
     * @param WorkflowResult $result
     * @throws \RuntimeException
     */
    public function buildSuccesses(WorkflowResult $result)
    {
        $results = $result->getResults();
        if ($results && isset($results[0]) && is_object($results[0])) {
            if ($results[0] instanceof \NS\SentinelBundle\Entity\BaseCase) {
                $this->buildCaseSuccess($results);
            }
            else if ($results[0] instanceof \NS\SentinelBundle\Entity\BaseExternalLab) {
                $this->buildExternalLabSuccess($results);
            }
            else {
                throw new \RuntimeException(sprintf("Unable to build success map for object of type: %s", get_class($results[0])));
            }
        }
    }

    /**
     *
     * @param array $results
     */
    public function buildExternalLabSuccess($results)
    {
        foreach ($results as $r) {
            $this->successes[] = array(
                'dbId'     => $r->getId(),
                'caseDbId' => $r->getCaseFile()->getId(),
                'labId'    => $r->getLabId(),
                'caseId'   => $r->getCaseFile()->getCaseId(),
                'site'     => $r->getCaseFile()->getSite()->getCode(),
                'siteName' => $r->getCaseFile()->getSite()->getName()
            );
        }
    }

    /**
     *
     * @param array $results
     */
    public function buildCaseSuccess($results)
    {
        foreach ($results as $r) {
            $this->successes[] = array(
                'id'       => $r->getId(),
                'caseId'   => $r->getCaseId(),
                'site'     => $r->getSite()->getCode(),
                'siteName' => $r->getSite()->getName()
            );
        }
    }

    /**
     * @param array $exceptions
     */
    public function buildExceptions(\SplObjectStorage $exceptions)
    {
        $exceptions->rewind();
        while($exceptions->valid()) {
            $object = $exceptions->current(); // similar to current($s)
            $row = $exceptions->offsetGet($object);
            $this->errors[$row] = array(
                'row'     => $row,
                'column'  => $object->getMessage(),
                'message' => ($object->getPrevious()) ? $object->getPrevious()->getMessage() : null
            );

            $exceptions->next();
        }
    }

    /**
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     *
     * @return integer
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     *
     * @return integer
     */
    public function getSuccessCount()
    {
        return $this->successCount;
    }

    /**
     *
     * @return integer
     */
    public function getErrorCount()
    {
        return $this->totalCount - $this->successCount;
    }

    /**
     *
     * @return string
     */
    public function getMapName()
    {
        return $this->mapName;
    }

    /**
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     *
     * @return array
     */
    public function getSuccesses()
    {
        return $this->successes;
    }

    /**
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->getSuccessCount() + $this->getDuplicateCount() + $this->getErrorCount();
    }

    /**
     *
     * @return integer
     */
    public function getDuplicateCount()
    {
        return count($this->duplicates);
    }

    /**
     *
     * @return array
     */
    public function getDuplicateMessages()
    {
        $out = array();
        foreach ($this->duplicates as $row => $msg) {
            $out[] = array('row' => $row, 'message' => $msg);
        }

        return $out;
    }

    /**
     *
     * @return array
     */
    public function getDuplicates()
    {
        return $this->duplicates;
    }

    /**
     *
     * @return integer
     */
    public function getTotalRowCount()
    {
        return $this->totalRowCount;
    }

    /**
     *
     * @return array
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

    /**
     *
     * @return integer
     */
    public function getWarningCount()
    {
        return count($this->warnings);
    }

    /**
     *
     * @param integer $totalRowCount
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setTotalRowCount($totalRowCount)
    {
        $this->totalRowCount = $totalRowCount;
        return $this;
    }

    /**
     *
     * @param array $warnings
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setWarnings(array $warnings)
    {
        $this->warnings = $warnings;
        return $this;
    }

    /**
     *
     * @param array $duplicates
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setDuplicates(array $duplicates)
    {
        $this->duplicates = $duplicates;
        return $this;
    }

    /**
     *
     * @param array $successes
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setSuccesses(array $successes = array())
    {
        $this->successes = $successes;
        return $this;
    }

    /**
     *
     * @param array $errors
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setErrors(array $errors = array())
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     *
     * @param \NS\SentinelBundle\Entity\User $user
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     *
     * @param string $filename
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     *
     * @param integer $totalCount
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;
        return $this;
    }

    /**
     *
     * @param integer $successCount
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setSuccessCount($successCount)
    {
        $this->successCount = $successCount;
        return $this;
    }

    /**
     *
     * @param string $mapName
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setMapName($mapName)
    {
        $this->mapName = $mapName;
        return $this;
    }

    /**
     * 
     * @return Map
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     *
     * @param \NS\ImportBundle\Entity\Map $map
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setMap(Map $map)
    {
        $this->map     = $map;
        $this->mapName = sprintf("%s (%s)", $this->map->getName(), $this->map->getVersion());

        return $this;
    }

        /**
     *
     * @return File
     */
    public function getImportFile()
    {
        return $this->importFile;
    }

    /**
     *
     * @param File $importFile
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setImportFile(File $importFile)
    {
        $this->importFile = $importFile;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getImportStartedAt()
    {
        return $this->importStartedAt;
    }

    /**
     * @return \DateTime
     */
    public function getImportEndedAt()
    {
        return $this->importEndedAt;
    }

    /**
     *
     * @param \DateTime $createdAt
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     *
     * @param \DateTime $importStartedAt
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setImportStartedAt(\DateTime $importStartedAt)
    {
        if(!$this->importStartedAt) {
            $this->importStartedAt = $importStartedAt;
        }

        return $this;
    }

    /**
     *
     * @param \DateTime $importEndedAt
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setImportEndedAt(\DateTime $importEndedAt)
    {
        $this->importEndedAt = $importEndedAt;
        return $this;
    }

    // Pass through functions

    /**
     * @return array
     */
    public function getConverters()
    {
        return $this->map->getConverters();
    }

    /**
     * @return array
     */
    public function getMappings()
    {
        return $this->map->getMappings();
    }

    /**
     * @return array
     */
    public function getIgnoredMapper()
    {
        return $this->map->getIgnoredMapper();
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->map->getClass();
    }
}
