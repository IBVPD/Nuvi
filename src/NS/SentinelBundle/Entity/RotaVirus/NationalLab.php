<?php

namespace NS\SentinelBundle\Entity\RotaVirus;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaKit;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Validators as LocalAssert;
use Symfony\Component\Validator\Constraints as Assert;
use NS\UtilBundle\Validator\Constraints as UtilAssert;
use JMS\Serializer\Annotation as Serializer;

/**
 * Description of ReferenceLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\RotaVirus\NationalLabRepository")
 * @ORM\Table(name="rota_national_labs")
 *
 * @LocalAssert\GreaterThanDate(atPath="dt_sample_recd",lessThanField="caseFile.siteLab.stoolSentToNLDate",greaterThanField="dateReceived",message="form.validation.vaccination-after-admission")
 * @LocalAssert\GreaterThanDate(atPath="dt_gt",lessThanField="dateReceived",greaterThanField="genotypingDate",message="form.validation.vaccination-after-admission")
 */
class NationalLab extends ExternalLab
{
    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\RotaVirus",inversedBy="nationalLab")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     * @ORM\Id
     */
    protected $caseFile;

    /** @var string */
    private $type = 'NL';

    /**
     * @var TripleChoice
     * @ORM\Column(name="elisaDone",type="TripleChoice",nullable=true)
     * @Assert\NotBlank()
     * @UtilAssert\ArrayChoiceConstraint()
     * @Serializer\Groups({"api","export"})
     */
    private $elisaDone;

    /**
     * @var ElisaKit
     * @ORM\Column(name="elisaKit",type="ElisaKit",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $elisaKit;

    /**
     * @var string|null
     * @ORM\Column(name="elisaKitOther",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $elisaKitOther;

    /**
     * @var string|null
     * @ORM\Column(name="elisaLoadNumber",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $elisaLoadNumber;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="elisaExpiryDate",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $elisaExpiryDate;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="elisaTestDate",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate
     */
    private $elisaTestDate;

    /**
     * @var ElisaResult $elisaResult
     * @ORM\Column(name="elisaResult",type="ElisaResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $elisaResult;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCaseFile()
    {
        return $this->caseFile;
    }

    /**
     * @param mixed $caseFile
     * @return $this|void
     */
    public function setCaseFile($caseFile = null)
    {
        $this->caseFile = $caseFile;
    }

    /**
     * @return TripleChoice
     */
    public function getElisaDone()
    {
        return $this->elisaDone;
    }

    /**
     * @param TripleChoice $elisaDone
     */
    public function setElisaDone($elisaDone)
    {
        $this->elisaDone = $elisaDone;
    }

    /**
     * @return ElisaKit
     */
    public function getElisaKit()
    {
        return $this->elisaKit;
    }

    /**
     * @param ElisaKit $elisaKit
     */
    public function setElisaKit($elisaKit)
    {
        $this->elisaKit = $elisaKit;
    }

    /**
     * @return string|null
     */
    public function getElisaKitOther()
    {
        return $this->elisaKitOther;
    }

    /**
     * @param string|null $elisaKitOther
     */
    public function setElisaKitOther(string $elisaKitOther)
    {
        $this->elisaKitOther = $elisaKitOther;
    }

    /**
     * @return string|null
     */
    public function getElisaLoadNumber()
    {
        return $this->elisaLoadNumber;
    }

    /**
     * @param string|null $elisaLoadNumber
     */
    public function setElisaLoadNumber(string $elisaLoadNumber)
    {
        $this->elisaLoadNumber = $elisaLoadNumber;
    }

    /**
     * @return \DateTime|null
     */
    public function getElisaExpiryDate()
    {
        return $this->elisaExpiryDate;
    }

    /**
     * @param \DateTime|null $elisaExpiryDate
     */
    public function setElisaExpiryDate($elisaExpiryDate)
    {
        $this->elisaExpiryDate = $elisaExpiryDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getElisaTestDate()
    {
        return $this->elisaTestDate;
    }

    /**
     * @param \DateTime|null $elisaTestDate
     */
    public function setElisaTestDate($elisaTestDate)
    {
        $this->elisaTestDate = $elisaTestDate;
    }

    public function getElisaResult()
    {
        return $this->elisaResult;
    }

    public function setElisaResult($elisaResult)
    {
        $this->elisaResult = $elisaResult;
    }
}
