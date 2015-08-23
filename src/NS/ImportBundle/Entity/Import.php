<?php

namespace NS\ImportBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \NS\SentinelBundle\Entity\User;
use \Symfony\Component\HttpFoundation\File\File;
use \Symfony\Component\Security\Core\User\UserInterface;
use \Vich\UploaderBundle\Mapping\Annotation as Vich;
use \Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\File
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
     * @ORM\Column(name="warnings",type="string",nullable=true)
     */
    private $warnings;

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
     * @ORM\Column(name="successes",type="string",nullable=true)
     */
    private $successes;

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
     * @ORM\Column(name="errors",type="string",nullable=true)
     */
    private $errors;

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
     * @ORM\Column(name="messages",type="string",nullable=true)
     */
    private $messages;

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
     * @ORM\Column(name="duplicates",type="string",nullable=true)
     */
    private $duplicates;
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

    /**
     * @var \NS\SentinelBundle\Entity\User $user
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Constructor
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
     * @return \NS\ImportBundle\Entity\Result
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
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     *
     * @param integer $processedCount
     * @return \NS\ImportBundle\Entity\Result
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
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setImportedCount($importedCount)
    {
        $this->importedCount = $importedCount;
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
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     *
     * @param \DateTime $startedAt
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setStartedAt(\DateTime $startedAt)
    {
        if(!$this->startedAt) {
            $this->startedAt = $startedAt;
        }

        return $this;
    }

    /**
     *
     * @param \DateTime $endedAt
     * @return \NS\ImportBundle\Entity\Result
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
     * @return \NS\ImportBundle\Entity\Result
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
     * @return \NS\ImportBundle\Entity\Result
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
     * @return Result
     */
    public function setWarningFile(File $warningFile)
    {
        $this->warningFile = $warningFile;
        return $this;
    }

    /**
     *
     * @param string $warnings
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setWarnings($warnings)
    {
        $this->warnings = $warnings;
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
     *
     * @param string $successes
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setSuccesses($successes)
    {
        $this->successes = $successes;
        return $this;
    }

    /**
     * @param File $successFile
     * @return Result
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
     * @return string
     */
    public function getDuplicates()
    {
        return $this->duplicates;
    }

    /**
     * @param string $duplicates
     * @return $this
     */
    public function setDuplicates($duplicates)
    {
        $this->duplicates = $duplicates;
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
     * @return Result
     */
    public function setErrorFile(File $errorFile)
    {
        $this->errorFile = $errorFile;
        return $this;
    }

    /**
     *
     * @param array $errors
     * @return \NS\ImportBundle\Entity\Result
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
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
        return ($this->sourceCount > 0) ? $this->sourceCount == $this->processedCount : false;
    }
    // =================================================================================================================
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
