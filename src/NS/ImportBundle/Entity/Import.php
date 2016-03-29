<?php

namespace NS\ImportBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Entity\ReferenceLab;
use \NS\SentinelBundle\Entity\User;
use \Symfony\Component\HttpFoundation\File\File;
use \Symfony\Component\Security\Core\User\UserInterface;
use \Vich\UploaderBundle\Mapping\Annotation as Vich;
use \Symfony\Component\Validator\Constraints as Assert;
use \NS\ImportBundle\Entity\Validator as LocalAssert;

/**
 * Description of Result
 *
 * @author gnat
 *
 * @ORM\Entity(repositoryClass="NS\ImportBundle\Repository\ImportRepository")
 * @ORM\Table(name="import_results")
 * 
 * @Vich\Uploadable
 *
 * @LocalAssert\Import()
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Import
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
     * @ORM\ManyToOne(targetEntity="Map")
     */
    private $map;

    /**
     * @var array $mapName
     * @ORM\Column(name="mapName",type="string")
     */
    private $mapName;

    /**
     * @var integer $position
     * @ORM\Column(name="position",type="integer")
     */
    private $position = 0;

    /**
     * @var \DateTime $inputDateStart
     * @ORM\Column(name="inputDateStart",type="date")
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $inputDateStart;

    /**
     * @var \DateTime $inputDateEnd
     * @ORM\Column(name="inputDateEnd",type="date")
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $inputDateEnd;

    /**
     * @var \NS\SentinelBundle\Entity\ReferenceLab $referenceLab
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\ReferenceLab")
     */
    private $referenceLab;

    // ---------------------------------------------------------------------------------------

    /**
     * @var \DateTime $createdAt
     * @ORM\Column(name="createdAt",type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime $startedAt
     * @ORM\Column(name="startedAt",type="datetime",nullable=true)
     */
    private $startedAt;

    /**
     * @var \DateTime $endedAt
     * @ORM\Column(name="endedAt",type="datetime",nullable=true)
     */
    private $endedAt;

    // ---------------------------------------------------------------------------------------

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="import_file", fileNameProperty="source")
     * @Assert\File()
     * @Assert\NotBlank()
     * @var File $imageFile
     */
    private $sourceFile;

    /**
     * @var string $source
     * @ORM\Column(name="source",type="string")
     */
    private $source;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="import_file", fileNameProperty="warnings")
     * @Assert\File
     * @var File $warningFile
     */
    private $warningFile;

    /**
     * @var string $warnings
     */
    private $warnings = 'warnings.csv';

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="import_file", fileNameProperty="successes")
     * @Assert\File
     * @var File $successFile
     */
    private $successFile;

    /**
     * @var string $successes
     */
    private $successes = 'successes.csv';

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="import_file", fileNameProperty="errors")
     * @Assert\File
     * @var File $errorFile
     */
    private $errorFile;

    /**
     * @var string $errors
     */
    private $errors = 'errors.csv';

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="import_file", fileNameProperty="messages")
     * @Assert\File
     * @var File $messageFile
     */
    private $messageFile;

    /**
     * @var string $messages
     */
    private $messages = 'messages.csv';

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="import_file", fileNameProperty="duplicates")
     * @Assert\File
     * @var File $duplicateFile
     */
    private $duplicateFile;

    /**
     * @var string $duplicates
     */
    private $duplicates = 'duplicate-state.txt';
    // ---------------------------------------------------------------------------------------

    /**
     * @var integer $sourceCount
     * @ORM\Column(name="sourceCount",type="integer",nullable=true)
     */
    private $sourceCount = 0;

    /**
     * @var integer $processedCount
     * @ORM\Column(name="processedCount",type="integer",nullable=true)
     */
    private $processedCount = 0;

    /**
     * @var int $skippedCount
     * @ORM\Column(name="skippedCount",type="integer",nullable=true)
     */
    private $skippedCount = 0;

    /**
     * @var integer $importedCount
     * @ORM\Column(name="importedCount",type="integer",nullable=true)
     */
    private $importedCount = 0;

    /**
     * @var integer $warningCount
     * @ORM\Column(name="warningCount",type="integer",nullable=true)
     */
    private $warningCount = 0;

    // ---------------------------------------------------------------------------------------
    /**
     * @var integer $pheanstalkJobId
     * @ORM\Column(name="pheanstalkJobId",type="integer",nullable=true)
     */
    private $pheanstalkJobId;

    /**
     * @var string $pheanstalkStatus
     * @ORM\Column(name="pheanstalkStatus",type="string",nullable=true)
     */
    private $pheanstalkStatus;

    /**
     * @var string
     * @ORM\Column(name="pheanstalkStackTrace",type="text",nullable=true)
     */
    private $pheanstalkStackTrace;

    /**
     * @var \NS\SentinelBundle\Entity\User $user
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Constructor
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->createdAt = new \DateTime();
        $this->user      = $user;
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
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return Import
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getInputDateStart()
    {
        return $this->inputDateStart;
    }

    /**
     * @param \DateTime $inputDateStart
     * @return Import
     */
    public function setInputDateStart(\DateTime $inputDateStart = null)
    {
        $this->inputDateStart = $inputDateStart;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getInputDateEnd()
    {
        return $this->inputDateEnd;
    }

    /**
     * @param \DateTime $inputDateEnd
     * @return Import
     */
    public function setInputDateEnd(\DateTime $inputDateEnd = null)
    {
        $this->inputDateEnd = $inputDateEnd;
        return $this;
    }

    /**
     * @return \NS\SentinelBundle\Entity\ReferenceLab
     */
    public function getReferenceLab()
    {
        return $this->referenceLab;
    }

    /**
     * @param \NS\SentinelBundle\Entity\ReferenceLab $referenceLab
     * @return Import
     */
    public function setReferenceLab(ReferenceLab $referenceLab = null)
    {
        $this->referenceLab = $referenceLab;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasReferenceLabResults()
    {
        if (!$this->referenceLab) {
            return false;
        }

        $mappings = $this->map->getMappedColumns();
        foreach ($mappings as $target) {
            if (strpos($target, 'referenceLab') !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * @return integer
     */
    public function getProcessedCount()
    {
        return $this->processedCount;
    }

    /**
     *
     * @return integer
     */
    public function getImportedCount()
    {
        return $this->importedCount;
    }

    /**
     * @param $importedCount
     * @return $this
     */
    public function incrementImportedCount($importedCount)
    {
        $this->importedCount += $importedCount;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getErrorCount()
    {
        return $this->processedCount - $this->importedCount;
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
     * @return integer
     */
    public function getTotal()
    {
        return $this->getImportedCount() + $this->getErrorCount();
    }

    /**
     *
     * @return integer
     */
    public function getSourceCount()
    {
        return $this->sourceCount;
    }

    /**
     *
     * @return integer
     */
    public function getWarningCount()
    {
        return $this->warningCount;
    }

    /**
     * @param int $warningCount
     * @return Import
     */
    public function setWarningCount($warningCount)
    {
        $this->warningCount = $warningCount;
        return $this;
    }

    /**
     * @param $warningCount
     * @return $this
     */
    public function incrementWarningCount($warningCount)
    {
        $this->warningCount += $warningCount;
        return $this;
    }

    /**
     *
     * @param integer $sourceCount
     * @return \NS\ImportBundle\Entity\Import
     */
    public function setSourceCount($sourceCount)
    {
        $this->sourceCount = $sourceCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getSkippedCount()
    {
        return $this->skippedCount;
    }

    /**
     * @param int $skippedCount
     * @return Import
     */
    public function setSkippedCount($skippedCount)
    {
        $this->skippedCount = $skippedCount;
        return $this;
    }

    /**
     *
     * @param \NS\SentinelBundle\Entity\User $user
     * @return \NS\ImportBundle\Entity\Import
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     *
     * @param integer $processedCount
     * @return \NS\ImportBundle\Entity\Import
     */
    public function setProcessedCount($processedCount)
    {
        $this->processedCount = $processedCount;
        return $this;
    }

    /**
     * @param $processedCount
     * @return $this
     */
    public function incrementProcessedCount($processedCount)
    {
        $this->processedCount += $processedCount;
        return $this;
    }

    /**
     *
     * @param integer $importedCount
     * @return \NS\ImportBundle\Entity\Import
     */
    public function setImportedCount($importedCount)
    {
        $this->importedCount = $importedCount;
        return $this;
    }

    /**
     *
     * @param string $mapName
     * @return \NS\ImportBundle\Entity\Import
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
     * @return \NS\ImportBundle\Entity\Import
     */
    public function setMap(Map $map)
    {
        $this->map     = $map;
        $this->mapName = sprintf("%s (%s)", $this->map->getName(), $this->map->getVersion());

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
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @return \DateTime
     */
    public function getEndedAt()
    {
        return $this->endedAt;
    }

    /**
     *
     * @param \DateTime $createdAt
     * @return \NS\ImportBundle\Entity\Import
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     *
     * @param \DateTime $startedAt
     * @return \NS\ImportBundle\Entity\Import
     */
    public function setStartedAt(\DateTime $startedAt)
    {
        if (!$this->startedAt) {
            $this->startedAt = $startedAt;
        }

        return $this;
    }

    /**
     *
     * @param \DateTime $endedAt
     * @return \NS\ImportBundle\Entity\Import
     */
    public function setEndedAt(\DateTime $endedAt)
    {
        $this->endedAt = $endedAt;
        return $this;
    }

    /**
     *
     * @return File
     */
    public function getSourceFile()
    {
        return $this->sourceFile;
    }

    /**
     *
     * @param File $sourceFile
     * @return \NS\ImportBundle\Entity\Import
     */
    public function setSourceFile(File $sourceFile)
    {
        $this->sourceFile = $sourceFile;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     *
     * @param string $source
     * @return \NS\ImportBundle\Entity\Import
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return File
     */
    public function getWarningFile()
    {
        return $this->warningFile;
    }

    /**
     * @param File $warningFile
     * @return Import
     */
    public function setWarningFile(File $warningFile)
    {
        $this->warningFile = $warningFile;
        return $this;
    }

    /**
     * @return File
     */
    public function getSuccessFile()
    {
        return $this->successFile;
    }

    /**
     * @param File $successFile
     * @return Import
     */
    public function setSuccessFile(File $successFile)
    {
        $this->successFile = $successFile;
        return $this;
    }

    /**
     * @return File
     */
    public function getMessageFile()
    {
        return $this->messageFile;
    }

    /**
     * @param File $messageFile
     * @return $this
     */
    public function setMessageFile(File $messageFile)
    {
        $this->messageFile = $messageFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param string $messages
     * @return $this
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * @return File
     */
    public function getDuplicateFile()
    {
        return $this->duplicateFile;
    }

    /**
     * @param File $duplicateFile
     * @return $this
     */
    public function setDuplicateFile(File $duplicateFile)
    {
        $this->duplicateFile = $duplicateFile;
        return $this;
    }

    /**
     * @return File
     */
    public function getErrorFile()
    {
        return $this->errorFile;
    }

    /**
     * @param File $errorFile
     * @return Import
     */
    public function setErrorFile(File $errorFile)
    {
        $this->errorFile = $errorFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

    /**
     * @param string $warnings
     * @return Import
     */
    public function setWarnings($warnings)
    {
        $this->warnings = $warnings;
        return $this;
    }

    /**
     * @return string
     */
    public function getSuccesses()
    {
        return $this->successes;
    }

    /**
     * @param string $successes
     * @return Import
     */
    public function setSuccesses($successes)
    {
        $this->successes = $successes;
        return $this;
    }

    /**
     * @return string
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param string $errors
     * @return Import
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @return string
     */
    public function getDuplicates()
    {
        return $this->duplicates;
    }

    /**
     * @param string $duplicates
     * @return Import
     */
    public function setDuplicates($duplicates)
    {
        $this->duplicates = $duplicates;
        return $this;
    }

    /**
     * @return int
     */
    public function getPheanstalkJobId()
    {
        return $this->pheanstalkJobId;
    }

    /**
     * @param int $pheanstalkJobId
     * @return Import
     */
    public function setPheanstalkJobId($pheanstalkJobId)
    {
        $this->pheanstalkJobId = $pheanstalkJobId;
        return $this;
    }

    /**
     * @return string
     */
    public function getPheanstalkStatus()
    {
        return $this->pheanstalkStatus;
    }

    const STATUS_RUNNING = 'running';
    const STATUS_PAUSED = 'paused';
    const STATUS_COMPLETE = 'complete';
    const STATUS_BURIED = 'buried';

    /**
     * @param string $pheanstalkStatus
     * @return Import
     */
    public function setPheanstalkStatus($pheanstalkStatus)
    {
        $this->pheanstalkStatus = $pheanstalkStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getPheanstalkStackTrace()
    {
        return $this->pheanstalkStackTrace;
    }

    /**
     * @param string $pheanstalkStackTrace
     * @return Import
     */
    public function setPheanstalkStackTrace($pheanstalkStackTrace)
    {
        $this->pheanstalkStackTrace = $pheanstalkStackTrace;
        return $this;
    }

    /**
     * @return int
     */
    public function getPercentComplete()
    {
        return (int)(($this->sourceCount>0)? ($this->processedCount/$this->sourceCount)*100:0);
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        return ($this->sourceCount > 0) ? ($this->sourceCount <= $this->processedCount): false;
    }

    /**
     * @return bool
     */
    public function isRunning()
    {
        return (!$this->isComplete() && $this->pheanstalkJobId && $this->pheanstalkStatus == self::STATUS_RUNNING);
    }

    /**
     * @return bool
     */
    public function isPaused()
    {
        return (!$this->isComplete() && $this->pheanstalkJobId && $this->pheanstalkStatus == self::STATUS_PAUSED);
    }

    /**
     * @return bool
     */
    public function isQueued()
    {
        return ($this->pheanstalkJobId && ($this->pheanstalkStatus === self::STATUS_RUNNING || $this->pheanstalkStatus == self::STATUS_PAUSED));
    }

    /**
     * @return bool
     */
    public function isBuried()
    {
        return ($this->pheanstalkStatus == self::STATUS_BURIED);
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return (!$this->isComplete() && $this->pheanstalkStatus == self::STATUS_BURIED);
    }

    // =================================================================================================================
    // Pass through functions

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->map->getClass();
    }

    /**
     * @return array
     */
    public function getConverters()
    {
        return $this->map->getConvertedColumns();
    }

    /**
     * @return array
     */
    public function getMappings()
    {
        return $this->map->getMappedColumns();
    }

    /**
     * @return array
     */
    public function getIgnoredMapper()
    {
        return $this->map->getIgnoredColumns();
    }

    /**
     * @return mixed
     */
    public function getPreprocessor()
    {
        return $this->map->getPreProcessorConditions();
    }

    /**
     * @return string
     */
    public function getCaseLinkerId()
    {
        return $this->map->getCaseLinker();
    }
}
