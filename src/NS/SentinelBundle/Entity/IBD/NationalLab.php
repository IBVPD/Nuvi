<?php

namespace NS\SentinelBundle\Entity\IBD;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use NS\SentinelBundle\Validators as LocalAssert;

/**
 * Description of ReferenceLab
 * @author gnat
 *
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\IBD\NationalLabRepository")
 * @ORM\Table(name="ibd_national_labs")
 * @ORM\HasLifecycleCallbacks
 */
class NationalLab extends ExternalLab
{
    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\IBD",inversedBy="nationalLab")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     * @ORM\Id
     */
    protected $caseFile;

    /**
     * @var boolean
     * @ORM\Column(name="rl_isol_csf_sent",type="boolean",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     */
    private $rl_isol_csf_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="rl_isol_csf_date",type="date",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $rl_isol_csf_date;

    /**
     * @var boolean
     * @ORM\Column(name="rl_isol_blood_sent",type="boolean",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     */
    private $rl_isol_blood_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="rl_isol_blood_date",type="date",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $rl_isol_blood_date;

    /**
     * @var boolean
     * @ORM\Column(name="rl_other_sent",type="boolean",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     */
    private $rl_other_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="rl_other_date",type="date",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $rl_other_date;

    /**
     * @var string
     */
    private $type        = 'NL';

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
     * @return bool
     */
    public function isRlIsolCsfSent()
    {
        return $this->rl_isol_csf_sent;
    }

    /**
     * @param bool $rl_isol_csf_sent
     */
    public function setRlIsolCsfSent($rl_isol_csf_sent)
    {
        $this->rl_isol_csf_sent = $rl_isol_csf_sent;
    }

    /**
     * @return \DateTime
     */
    public function getRlIsolCsfDate()
    {
        return $this->rl_isol_csf_date;
    }

    /**
     * @param \DateTime $rl_isol_csf_date
     */
    public function setRlIsolCsfDate(\DateTime $rl_isol_csf_date = null)
    {
        $this->rl_isol_csf_date = $rl_isol_csf_date;
    }

    /**
     * @return bool
     */
    public function isRlIsolBloodSent()
    {
        return $this->rl_isol_blood_sent;
    }

    /**
     * @param bool $rl_isol_blood_sent
     */
    public function setRlIsolBloodSent($rl_isol_blood_sent)
    {
        $this->rl_isol_blood_sent = $rl_isol_blood_sent;
    }

    /**
     * @return \DateTime
     */
    public function getRlIsolBloodDate()
    {
        return $this->rl_isol_blood_date;
    }

    /**
     * @param \DateTime $rl_isol_blood_date
     */
    public function setRlIsolBloodDate(\DateTime $rl_isol_blood_date = null)
    {
        $this->rl_isol_blood_date = $rl_isol_blood_date;
    }

    /**
     * @return bool
     */
    public function isRlOtherSent()
    {
        return $this->rl_other_sent;
    }

    /**
     * @param bool $rl_other_sent
     */
    public function setRlOtherSent($rl_other_sent)
    {
        $this->rl_other_sent = $rl_other_sent;
    }

    /**
     * @return \DateTime
     */
    public function getRlOtherDate()
    {
        return $this->rl_other_date;
    }

    /**
     * @param \DateTime $rl_other_date
     */
    public function setRlOtherDate(\DateTime $rl_other_date = null)
    {
        $this->rl_other_date = $rl_other_date;
    }

    /**
     * @return bool
     */
    public function getSentToReferenceLab()
    {
        return ($this->rl_isol_csf_sent || $this->rl_isol_blood_sent || $this->rl_other_sent);
    }
}
