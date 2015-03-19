<?php

namespace NS\ImportBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \NS\ImportBundle\Writer\Result as WorkflowResult;
use \NS\SentinelBundle\Entity\User;

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
     * @var \DateTime $importedAt
     * @ORM\Column(name="importedAt",type="datetime")
     */
    private $importedAt;

    /**
     * @var string $filename
     * @ORM\Column(name="filename",type="string")
     */
    private $filename;

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
     * @var User $user
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\User")
     */
    private $user;

    public function __construct(Import $import, WorkflowResult $result)
    {
        $this->buildSuccesses($result);
        $this->buildExceptions($result);

        $this->totalCount   = $result->getTotalProcessedCount();
        $this->successCount = $result->getSuccessCount();
        $this->importedAt   = $result->getEndtime();
        $this->duplicates   = $result->getDuplicates()->toArray();
        $this->mapName      = sprintf("%s (%s)", $import->getMap()->getName(), $import->getMap()->getVersion());
        $this->filename     = $import->getFile()->getClientOriginalName();
    }

    public function buildSuccesses(WorkflowResult $result)
    {
        $results = $result->getResults();
        if ($results && isset($results[0]) && is_object($results[0]))
        {
            if ($results[0] instanceof \NS\SentinelBundle\Entity\BaseCase)
            {
                $this->buildCaseSuccess($results);
            }
            else if ($results[0] instanceof \NS\SentinelBundle\Entity\BaseExternalLab)
            {
                $this->buildExternalLabSuccess($results);
            }
            else
            {
                throw new \RuntimeException(sprintf("Unable to build success map for object of type: %s", get_class($results[0])));
            }
        }
    }

    public function buildExternalLabSuccess($results)
    {
        foreach ($results as $r)
        {
            $this->successes[] = array(
                'id'       => $r->getId(),
                'labId'    => $r->getLabId(),
                'caseId'   => $r->getCaseFile()->getCaseId(),
                'site'     => $r->getCaseFile()->getSite()->getCode(),
                'siteName' => $r->getCaseFile()->getSite()->getName()
            );
        }
    }

    public function buildCaseSuccess($results)
    {
        foreach ($results as $r)
        {
            $this->successes[] = array(
                'id'       => $r->getId(),
                'caseId'   => $r->getCaseId(),
                'site'     => $r->getSite()->getCode(),
                'siteName' => $r->getSite()->getName()
            );
        }
    }

    public function buildExceptions(WorkflowResult $result)
    {
        foreach ($result->getExceptions() as $row => $e)
        {
            $this->errors[$row] = array(
                'row'     => $row,
                'column'  => $e->getMessage(),
                'message' => ($e->getPrevious()) ? $e->getPrevious()->getMessage() : null
            );
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
     * @return \DateTime
     */
    public function getImportedAt()
    {
        return $this->importedAt;
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
        foreach ($this->duplicates as $row => $msg)
        {
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
     * @param \DateTime $importedAt
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setImportedAt($importedAt)
    {
        $this->importedAt = $importedAt;
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
}
