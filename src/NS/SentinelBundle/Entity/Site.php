<?php

namespace NS\SentinelBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function is_integer;
use JMS\Serializer\Annotation\Groups;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;
use NS\SentinelBundle\Form\IBD\Types\IntenseSupport;
use NS\SentinelBundle\Form\Types\SurveillanceConducted;
use Serializable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Site
 *
 * @ORM\Table(name="sites")
 * @ORM\Entity(repositoryClass="\NS\SentinelBundle\Repository\SiteRepository")
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_SUPER_ADMIN"},enabled=false),
 *      @SecuredCondition(roles={"ROLE_REGION"},relation="region",through={"country"},class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY","ROLE_RRL_LAB","ROLE_NL_LAB"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},field="code"),
 *      }) 
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @UniqueEntity(fields={"code"})
 *
 */
class Site implements Serializable
{
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=15)
     * @ORM\Id
     * @Groups({"user","api"})
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Groups({"user","api"})
     */
    private $name;

    /**
     * @var integer|null
     * @ORM\Column(name="rvYearIntro",type="integer",nullable=true)
     * @Assert\GreaterThan(value=1900)
     * @Groups({"user"})
     */
    private $rvYearIntro;

    /**
     * @var integer|null
     * @ORM\Column(name="ibdYearIntro",type="integer",nullable=true)
     * @Assert\GreaterThan(value=1900)
     * @Groups({"user"})
     */
    private $ibdYearIntro;

    /**
     * @var string|null
     *
     * @ORM\Column(name="street", type="string", length=255,nullable=true)
     * @Groups({"user"})
     */
    private $street;

    /**
     * @var string|null
     *
     * @ORM\Column(name="city", type="string", length=255,nullable=true)
     * @Groups({"user"})
     */
    private $city;

    /**
     * @var integer|null
     * @ORM\Column(name="numberOfBeds",type="integer",nullable=true)
     * @Groups({"user"})
     */
    private $numberOfBeds;

    /**
     * @var string|null
     *
     * @ORM\Column(name="website", type="string", length=255,nullable=true)
     * @Assert\Url()
     * @Groups({"user"})
     */
    private $website;

    /**
     * @var integer
     *
     * @ORM\Column(name="currentCaseId", type="integer")
     * @Groups({"user"})
     */
    private $currentCaseId = 1;

    /**
     * @var SurveillanceConducted|null
     * @ORM\Column(name="surveillanceConducted",type="SurveillanceConducted",nullable=false)
     */
    private $surveillanceConducted;

    /**
     * @var int|null
     * @ORM\Column(name="ibdTier",type="integer",nullable=true)
     */
    private $ibdTier;

    /**
     * @var IntenseSupport|null
     * @ORM\Column(name="ibdIntenseSupport",type="IntenseSupport",nullable=true)
     */
    private $ibdIntenseSupport;

    /**
     * @var DateTime|null
     * @ORM\Column(name="ibdLastSiteAssessment",type="date",nullable=true)
     */
    private $ibdLastSiteAssessmentDate;

    /**
     * @var int|null
     * @ORM\Column(name="ibdSiteAssessmentScore",type="integer",nullable=true)
     */
    private $ibdSiteAssessmentScore;

    /**
     * @var DateTime|null
     * @ORM\Column(name="rvLastSiteAssessmentDate",type="date",nullable=true)
     */
    private $rvLastSiteAssessmentDate;

    /**
     * @var string|null
     * @ORM\Column(name="ibvpdRl",type="string",nullable=true)
     */
    private $ibvpdRl;

    /**
     * @var string|null
     * @ORM\Column(name="rvRl",type="string",nullable=true)
     */
    private $rvRl;

    /**
     * @var string|null
     * @ORM\Column(name="ibdEqaCode",type="string",nullable=true)
     */
    private $ibdEqaCode;

    /**
     * @var string|null
     * @ORM\Column(name="rvEqaCode",type="string",nullable=true)
     */
    private $rvEqaCode;

    /**
     * @var Country
     * 
     * @ORM\ManyToOne(targetEntity="Country",inversedBy="sites")
     * @ORM\JoinColumn(referencedColumnName="code")
     * @Groups({"user"})
     */
    private $country;

    /**
     * @var boolean|null
     * @ORM\Column(name="active",type="boolean",nullable=false)
     */
    private $active = true;

    /**
     * @var ZeroReport[]|Collection $zeroReports
     * @ORM\OneToMany(targetEntity="NS\SentinelBundle\Entity\ZeroReport", mappedBy="site")
     */
    private $zeroReports;

    /**
     * @var bool|null
     * @ORM\Column(name="tac_phase2",type="boolean",nullable=true)
     */
    private $tacPhase2 = false;

    //Fields used for reporting etc...

    /** @var int|null */
    private $totalCases;

    public function __construct(?string $code = null, ?string $name = null)
    {
        $this->code = $code;
        $this->name = $name;
    }

    public function __toString()
    {
        if (mb_strlen($this->name, 'UTF-8') > 20) {
            return mb_substr($this->name, 0, 31, 'UTF-8') . '...';
        }

        if ($this->name === null) {
            return sprintf('No Name - %s', $this->code);
        }

        return sprintf('%s', $this->name);
    }

    public function getAjaxDisplay(): string
    {
        return sprintf('%s - %s',$this->country->getCode(),$this->__toString());
    }

    public function getId(): ?string
    {
        return $this->code;
    }

    public function hasId(): bool
    {
        return (!empty($this->code) || $this->code !== null);
    }

    public function setId(string $id): void
    {
        $this->code = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setCountry(Country $country = null): void
    {
        $this->country = $country;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setRvYearIntro(int $rvYearIntro): void
    {
        $this->rvYearIntro = $rvYearIntro;
    }

    public function getRvYearIntro(): ?int
    {
        return $this->rvYearIntro;
    }

    public function setIbdYearIntro(int $ibdYearIntro): void
    {
        $this->ibdYearIntro = $ibdYearIntro;
    }

    public function getIbdYearIntro(): ?int
    {
        return $this->ibdYearIntro;
    }

    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setNumberOfBeds(?int $numberOfBeds): void
    {
        $this->numberOfBeds = $numberOfBeds;
    }

    public function getNumberOfBeds(): ?int
    {
        return $this->numberOfBeds;
    }

    public function setWebsite(?string $website): void
    {
        $this->website = $website;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function getCurrentCaseId(): ?int
    {
        return $this->currentCaseId;
    }

    public function setCurrentCaseId(int $currentCaseId): void
    {
        $this->currentCaseId = $currentCaseId;
    }

    public function getIbdTier(): ?int
    {
        return $this->ibdTier;
    }

    public function getIbdIntenseSupport(): ?IntenseSupport
    {
        return $this->ibdIntenseSupport;
    }

    public function getIbdLastSiteAssessmentDate(): ?DateTime
    {
        return $this->ibdLastSiteAssessmentDate;
    }

    public function getIbdSiteAssessmentScore(): ?int
    {
        return $this->ibdSiteAssessmentScore;
    }

    public function getRvLastSiteAssessmentDate(): ?DateTime
    {
        return $this->rvLastSiteAssessmentDate;
    }

    public function getIbvpdRl(): ?string
    {
        return $this->ibvpdRl;
    }

    public function getRvRl(): ?string
    {
        return $this->rvRl;
    }

    public function getIbdEqaCode(): ?string
    {
        return $this->ibdEqaCode;
    }

    public function getRvEqaCode(): ?string
    {
        return $this->rvEqaCode;
    }

    public function getSurveillanceConducted(): ?SurveillanceConducted
    {
        return $this->surveillanceConducted;
    }

    public function setSurveillanceConducted(?SurveillanceConducted $surveillanceConducted): void
    {
        $this->surveillanceConducted = $surveillanceConducted;
    }

    public function setIbdTier(?int $ibdTier): void
    {
        $this->ibdTier = $ibdTier;
    }

    public function setIbdIntenseSupport(?IntenseSupport $ibdIntenseSupport): void
    {
        $this->ibdIntenseSupport = $ibdIntenseSupport;
    }

    public function setIbdLastSiteAssessmentDate(?DateTime $ibdLastSiteAssessmentDate = null): void
    {
        $this->ibdLastSiteAssessmentDate = $ibdLastSiteAssessmentDate;
    }

    public function setIbdSiteAssessmentScore(?int $ibdSiteAssessmentScore): void
    {
        $this->ibdSiteAssessmentScore = $ibdSiteAssessmentScore;
    }

    public function setRvLastSiteAssessmentDate(?DateTime $rvLastSiteAssessmentDate = null): void
    {
        $this->rvLastSiteAssessmentDate = $rvLastSiteAssessmentDate;
    }

    public function setIbvpdRl(?string $ibvpdRl): void
    {
        $this->ibvpdRl = $ibvpdRl;
    }

    public function setRvRl(?string $rvRl): void
    {
        $this->rvRl = $rvRl;
    }

    public function setIbdEqaCode(?string $ibdEqaCode): void
    {
        $this->ibdEqaCode = $ibdEqaCode;
    }

    public function setRvEqaCode(?string $rvEqaCode): void
    {
        $this->rvEqaCode = $rvEqaCode;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getZeroReports(): ?Collection
    {
        return $this->zeroReports;
    }

    /**
     * @param Collection $zeroReports
     * @return Site
     */
    public function setZeroReports($zeroReports): ?Site
    {
        $this->zeroReports = new ArrayCollection();
        foreach ($zeroReports as $report) {
            $this->addZeroReport($report);
        }
    }

    public function addZeroReport(ZeroReport $report): void
    {
        $report->setSite($this);
        $this->zeroReports->add($report);
    }

    public function removeZeroReport(ZeroReport $report): void
    {
        if ($this->zeroReports->contains($report)) {
            $this->zeroReports->removeElement($report);
        }
    }

    public function isTacPhase2(): ?bool
    {
        return $this->tacPhase2;
    }

    public function setTacPhase2(?bool $tacPhase2): void
    {
        $this->tacPhase2 = $tacPhase2;
    }

    public function serialize(): string
    {
        return serialize([
            $this->code,
            $this->name,
            $this->website,
            $this->rvYearIntro,
            $this->ibdYearIntro,
            $this->street,
            $this->city,
            $this->numberOfBeds,
            $this->ibdTier,
            $this->ibdIntenseSupport,
            $this->ibdLastSiteAssessmentDate,
            $this->ibdSiteAssessmentScore,
            $this->rvLastSiteAssessmentDate,
            $this->ibvpdRl,
            $this->rvRl,
            $this->ibdEqaCode,
            $this->rvEqaCode,
            $this->surveillanceConducted,
            $this->country,

        ]);
    }

    public function unserialize($serialized): void
    {
        [
            $this->code,
            $this->name,
            $this->website,
            $this->rvYearIntro,
            $this->ibdYearIntro,
            $this->street,
            $this->city,
            $this->numberOfBeds,
            $this->ibdTier,
            $this->ibdIntenseSupport,
            $this->ibdLastSiteAssessmentDate,
            $this->ibdSiteAssessmentScore,
            $this->rvLastSiteAssessmentDate,
            $this->ibvpdRl,
            $this->rvRl,
            $this->ibdEqaCode,
            $this->rvEqaCode,
            $this->surveillanceConducted,
            $this->country,
        ] = unserialize($serialized, [__CLASS__]);
    }

    public function getTotalCases(): ?int
    {
        return $this->totalCases;
    }

    public function setTotalCases(int $totalCases): void
    {
        $this->totalCases = $totalCases;
    }
}
