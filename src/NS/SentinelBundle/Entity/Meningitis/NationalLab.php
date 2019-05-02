<?php

namespace NS\SentinelBundle\Entity\Meningitis;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use NS\SentinelBundle\Validators as LocalAssert;

/**
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Meningitis\NationalLabRepository")
 * @ORM\Table(name="mening_national_labs")
 * @ORM\EntityListeners(value={"NS\SentinelBundle\Entity\Listener\BaseExternalLabListener"})
 */
class NationalLab extends ExternalLab
{
    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Meningitis\Meningitis",inversedBy="nationalLab")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     * @ORM\Id
     */
    protected $caseFile;

    /**
     * @var bool|null
     * @ORM\Column(name="rl_isol_csf_sent",type="boolean",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     */
    private $rl_isol_csf_sent;

    /**
     * @var DateTime|null
     * @ORM\Column(name="rl_isol_csf_date",type="date",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $rl_isol_csf_date;

    /**
     * @var bool|null
     * @ORM\Column(name="rl_isol_blood_sent",type="boolean",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     */
    private $rl_isol_blood_sent;

    /**
     * @var DateTime|null
     * @ORM\Column(name="rl_isol_blood_date",type="date",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $rl_isol_blood_date;

    /**
     * @var bool|null
     * @ORM\Column(name="rl_other_sent",type="boolean",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     */
    private $rl_other_sent;

    /**
     * @var DateTime|null
     * @ORM\Column(name="rl_other_date",type="date",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $rl_other_date;

    /** @var string */
    private $type = 'NL';

    public function getType(): string
    {
        return $this->type;
    }

    public function isRlIsolCsfSent(): ?bool
    {
        return $this->rl_isol_csf_sent;
    }

    public function setRlIsolCsfSent(?bool $rl_isol_csf_sent)
    {
        $this->rl_isol_csf_sent = $rl_isol_csf_sent;
    }

    public function getRlIsolCsfDate(): ?DateTime
    {
        return $this->rl_isol_csf_date;
    }

    public function setRlIsolCsfDate(?DateTime $rl_isol_csf_date = null)
    {
        $this->rl_isol_csf_date = $rl_isol_csf_date;
    }

    public function isRlIsolBloodSent(): ?bool
    {
        return $this->rl_isol_blood_sent;
    }

    public function setRlIsolBloodSent(?bool $rl_isol_blood_sent)
    {
        $this->rl_isol_blood_sent = $rl_isol_blood_sent;
    }

    public function getRlIsolBloodDate(): ?DateTime
    {
        return $this->rl_isol_blood_date;
    }

    public function setRlIsolBloodDate(?DateTime $rl_isol_blood_date = null)
    {
        $this->rl_isol_blood_date = $rl_isol_blood_date;
    }

    public function isRlOtherSent(): ?bool
    {
        return $this->rl_other_sent;
    }

    public function setRlOtherSent(?bool $rl_other_sent)
    {
        $this->rl_other_sent = $rl_other_sent;
    }

    public function getRlOtherDate(): ?DateTime
    {
        return $this->rl_other_date;
    }

    public function setRlOtherDate(?DateTime $rl_other_date = null)
    {
        $this->rl_other_date = $rl_other_date;
    }

    public function getSentToReferenceLab(): bool
    {
        return ($this->rl_isol_csf_sent || $this->rl_isol_blood_sent || $this->rl_other_sent);
    }
}
