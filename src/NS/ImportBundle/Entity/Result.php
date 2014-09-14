<?php

namespace NS\ImportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ddeboer\DataImport\Result as WorkflowResult;

/**
 * Description of Result
 *
 * @author gnat
 *
 * @ORM\Entity(repositoryClass="NS\ImportBundle\Repository\Result")
 * @ORM\Table(name="import_results")
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
     * @ORM\Column(name="importedAt",type="date")
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
    private $successes;

    /**
     * @var array $errors
     * @ORM\Column(name="errors",type="array")
     */
    private $errors;

    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\User")
     */
    private $user;

    public function __construct(Import $import, WorkflowResult $result)
    {
        $this->successes = array();
        foreach($result->getResults() as $r)
            $this->successes[] = array('id'=>$r->getId(),'caseId'=>$r->getCaseId(),'site'=>$r->getSite()->getCode(),'siteName' => $r->getSite()->getName());

        $this->errors = array();
        foreach($result->getExceptions() as $row => $e)
        {
            $msg2 = ($e->getPrevious()) ? $e->getPrevious()->getMessage():null;
            $this->errors[$row] = array('row' => $row, 'column' => $e->getMessage(),'message'=>$msg2);
        }

        $this->totalCount = $result->getTotalProcessedCount();
        $this->successCount = $result->getSuccessCount();
        $this->importedAt = $result->getEndtime();
        $this->mapName    = sprintf("%s (%s)",$import->getMap()->getName(),$import->getMap()->getVersion());
        $this->filename   = $import->getFile()->getClientOriginalName();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getImportedAt()
    {
        return $this->importedAt;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getTotalCount()
    {
        return $this->totalCount;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getErrorCount()
    {
        return $this->totalCount-$this->successCount;
    }

    public function getMapName()
    {
        return $this->mapName;
    }

    public function getUser()
    {
        return $this->user;
    }

    function getSuccesses()
    {
        return $this->successes;
    }

    function getErrors()
    {
        return $this->errors;
    }

    function setSuccesses(array $successes = array())
    {
        $this->successes = $successes;
        return $this;
    }

    function setErrors(array $errors = array())
    {
        $this->errors = $errors;
        return $this;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function setImportedAt($importedAt)
    {
        $this->importedAt = $importedAt;
        return $this;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;
        return $this;
    }

    public function setSuccessCount($successCount)
    {
        $this->successCount = $successCount;
        return $this;
    }

    public function setMapName($mapName)
    {
        $this->mapName = $mapName;
        return $this;
    }
}
