<?php

namespace NS\SentinelBundle\Entity\Pneumonia;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Validators as LocalAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Pneumonia\NationalLabRepository")
 * @ORM\Table(name="pneu_national_labs")
 * @ORM\EntityListeners(value={"NS\SentinelBundle\Entity\Listener\BaseExternalLabListener"})
 */
class NationalLab extends ExternalLab
{
    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Pneumonia\Pneumonia",inversedBy="nationalLab")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     * @ORM\Id
     */
    protected $caseFile;

    /**
     * @var bool|null
     * @ORM\Column(name="rl_isol_blood_sent",type="boolean",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"Completeness"})
     */
    private $rl_isol_blood_sent;

    /**
     * @var DateTime|null
     * @ORM\Column(name="rl_isol_blood_date",type="date",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Assert\Expression(groups={"Completeness"},
     *     expression="this.isRlIsolBloodSent() and value === null",
     *     message="Blood Isol sample was sent to the RRL but date sent was not provided")
     */
    private $rl_isol_blood_date;

    /**
     * @var bool|null
     * @ORM\Column(name="rl_other_sent",type="boolean",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"Completeness"})
     */
    private $rl_other_sent;

    /**
     * @var DateTime|null
     * @ORM\Column(name="rl_other_date",type="date",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Assert\Expression(groups={"Completeness"},
     *     expression="this.isRlOtherSent() and value === null",
     *     message="Other sample was sent to the RRL but date sent was not provided")
     */
    private $rl_other_date;

    /** @var string */
    private $type = 'NL';

    public function getType(): string
    {
        return $this->type;
    }

    public function setCaseFile(?BaseCase $caseFile = null): void
    {
        $this->caseFile = $caseFile;
    }

    public function isRlIsolBloodSent(): ?bool
    {
        return $this->rl_isol_blood_sent;
    }

    public function setRlIsolBloodSent(?bool $rl_isol_blood_sent): void
    {
        $this->rl_isol_blood_sent = $rl_isol_blood_sent;
    }

    public function getRlIsolBloodDate(): ?DateTime
    {
        return $this->rl_isol_blood_date;
    }

    public function setRlIsolBloodDate(?DateTime $rl_isol_blood_date = null): void
    {
        $this->rl_isol_blood_date = $rl_isol_blood_date;
    }

    public function isRlOtherSent(): ?bool
    {
        return $this->rl_other_sent;
    }

    public function setRlOtherSent(?bool $rl_other_sent): void
    {
        $this->rl_other_sent = $rl_other_sent;
    }

    public function getRlOtherDate(): ?DateTime
    {
        return $this->rl_other_date;
    }

    public function setRlOtherDate(?DateTime $rl_other_date = null): void
    {
        $this->rl_other_date = $rl_other_date;
    }

    public function getSentToReferenceLab(): bool
    {
        return ($this->rl_isol_blood_sent || $this->rl_other_sent);
    }
}
