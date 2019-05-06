<?php

namespace NS\SentinelBundle\Entity\RotaVirus;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaKit;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Validators as LocalAssert;
use Symfony\Component\Validator\Constraints as Assert;
use NS\UtilBundle\Validator\Constraints\ArrayChoiceConstraint;

/**
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\RotaVirus\NationalLabRepository")
 * @ORM\Table(name="rota_national_labs")
 * @ORM\EntityListeners(value={"NS\SentinelBundle\Entity\Listener\BaseExternalLabListener"})
 *
 * @LocalAssert\GreaterThanDate(atPath="dt_sample_recd",lessThanField="caseFile.siteLab.stoolSentToNLDate",greaterThanField="dateReceived",message="form.validation.vaccination-after-admission")
 * @LocalAssert\GreaterThanDate(atPath="dt_gt",lessThanField="dateReceived",greaterThanField="genotypingDate",message="form.validation.vaccination-after-admission")
 *
 * @LocalAssert\Other(groups={"Completeness"},field="elisaDone",otherField="elisaKit",value="NS\SentinelBundle\Form\Types\TripleChoice::YES")
 * @LocalAssert\Other(groups={"Completeness"},field="elisaDone",otherField="elisaLoadNumber",value="NS\SentinelBundle\Form\Types\TripleChoice::YES")
 * @LocalAssert\Other(groups={"Completeness"},field="elisaDone",otherField="elisaExpiryDate",value="NS\SentinelBundle\Form\Types\TripleChoice::YES")
 * @LocalAssert\Other(groups={"Completeness"},field="elisaDone",otherField="elisaTestDate",value="NS\SentinelBundle\Form\Types\TripleChoice::YES")
 * @LocalAssert\Other(groups={"Completeness"},field="elisaDone",otherField="elisaResult",value="NS\SentinelBundle\Form\Types\TripleChoice::YES")
 * @LocalAssert\Other(groups={"Completeness"},field="elisaDone",otherField="stoolSentToRRL",value="NS\SentinelBundle\Form\Types\TripleChoice::YES")
 * @LocalAssert\Other(groups={"Completeness"},field="elisaKit",otherField="elisaKitOther",value="NS\SentinelBundle\Form\RotaVirus\Types\ElisaKit::OTHER")
 * @LocalAssert\Other(groups={"Completeness"},field="stoolSentToRRL",otherField="stoolSentToRRLDate",value="NS\SentinelBundle\Form\Types\TripleChoice::YES")
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
     * @var TripleChoice|null
     * @ORM\Column(name="elisaDone",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $elisaDone;

    /**
     * @var ElisaKit|null
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
     * @var DateTime|null
     * @ORM\Column(name="elisaExpiryDate",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $elisaExpiryDate;

    /**
     * @var DateTime|null
     * @ORM\Column(name="elisaTestDate",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate
     */
    private $elisaTestDate;

    /**
     * @var ElisaResult|null
     * @ORM\Column(name="elisaResult",type="ElisaResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $elisaResult;

    /**
     * RRL_stool_sent
     * @var TripleChoice|null
     * @ORM\Column(name="stoolSentToRRL",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $stoolSentToRRL; // These are duplicated from the boolean fields in the class we extend

    /**
     * RRL_stool_date
     * @var DateTime|null
     * @ORM\Column(name="stoolSentToRRLDate",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate
     * @Assert\NotBlank(groups={"Completeness"})
     */
    private $stoolSentToRRLDate;

    public function getType(): string
    {
        return $this->type;
    }

    public function getElisaDone(): ?TripleChoice
    {
        return $this->elisaDone;
    }

    public function setElisaDone(?TripleChoice $elisaDone): void
    {
        $this->elisaDone = $elisaDone;
    }

    public function getElisaKit(): ?ElisaKit
    {
        return $this->elisaKit;
    }

    public function setElisaKit(?ElisaKit $elisaKit): void
    {
        $this->elisaKit = $elisaKit;
    }

    public function getElisaKitOther(): ?string
    {
        return $this->elisaKitOther;
    }

    public function setElisaKitOther(?string $elisaKitOther): void
    {
        $this->elisaKitOther = $elisaKitOther;
    }

    public function getElisaLoadNumber(): ?string
    {
        return $this->elisaLoadNumber;
    }

    public function setElisaLoadNumber(?string $elisaLoadNumber): void
    {
        $this->elisaLoadNumber = $elisaLoadNumber;
    }

    public function getElisaExpiryDate(): ?DateTime
    {
        return $this->elisaExpiryDate;
    }

    public function setElisaExpiryDate(?DateTime $elisaExpiryDate): void
    {
        $this->elisaExpiryDate = $elisaExpiryDate;
    }

    public function getElisaTestDate(): ?DateTime
    {
        return $this->elisaTestDate;
    }

    public function setElisaTestDate(?DateTime $elisaTestDate): void
    {
        $this->elisaTestDate = $elisaTestDate;
    }

    public function getElisaResult(): ?ElisaResult
    {
        return $this->elisaResult;
    }

    public function setElisaResult(?ElisaResult $elisaResult): void
    {
        $this->elisaResult = $elisaResult;
    }

    public function getStoolSentToRRL(): ?TripleChoice
    {
        return $this->stoolSentToRRL;
    }

    public function setStoolSentToRRL(?TripleChoice $stoolSentToRRL): void
    {
        $this->stoolSentToRRL = $stoolSentToRRL;
    }

    public function getStoolSentToRRLDate(): ?DateTime
    {
        return $this->stoolSentToRRLDate;
    }

    public function setStoolSentToRRLDate(?DateTime $stoolSentToRRLDate): void
    {
        $this->stoolSentToRRLDate = $stoolSentToRRLDate;
    }

    public function getSentToReferenceLab(): bool
    {
        return $this->stoolSentToRRL && $this->stoolSentToRRL->equal(TripleChoice::YES);
    }
}
