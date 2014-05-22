<?php

namespace NS\SentinelBundle\Entity\IBD;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Form\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\Types\SerotypeIdentifier;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Description of ExternalLabSample
 *
 * @author gnat
 * 
 * @ORM\Entity()
 * @ORM\Table(name="ibd_external_lab_samples")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 */
class ExternalLabSample
{
    /**
     * @var integer $id
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var ExternalLab $lab
     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\IBD\ExternalLab", inversedBy="samples")
     */
    private $lab;

    /**
     * @var PathogenIdentifier
     * @ORM\Column(name="pathogenIdentifierMethod",type="PathogenIdentifier",nullable=true)
     */
    private $pathogenIdentifierMethod;

    /**
     * @var string
     * @ORM\Column(name="pathogenIdentifierOther", type="string",nullable=true)
     */
    private $pathogenIdentifierOther;

    /**
     * @var SerotypeIdentifier
     * @ORM\Column(name="serotypeIdentifier",type="SerotypeIdentifier",nullable=true)
     */
    private $serotypeIdentifier;

    /**
     * @var string
     * @ORM\Column(name="serotypeIdentifierOther",type="string",nullable=true)
     */
    private $serotypeIdentifierOther;

    /**
     * @var double
     * @ORM\Column(name="lytA",type="decimal",precision=3, scale=1,nullable=true)
     */
    private $lytA;

    /**
     * @var double
     * @ORM\Column(name="sodC",type="decimal",precision=3, scale=1,nullable=true)
     */
    private $sodC;

    /**
     * @var double
     * @ORM\Column(name="hpd",type="decimal",precision=3, scale=1,nullable=true)
     */
    private $hpd;

    /**
     * @var double
     * @ORM\Column(name="rNaseP",type="decimal",precision=3, scale=1,nullable=true)
     */
    private $rNaseP;

    /**
     * @var double
     * @ORM\Column(name="spnSerotype",type="decimal",precision=3, scale=1,nullable=true)
     */
    private $spnSerotype;

    /**
     * @var double
     * @ORM\Column(name="hiSerotype",type="decimal",precision=3, scale=1,nullable=true)
     */
    private $hiSerotype;

    /**
     * @var double
     * @ORM\Column(name="nmSerogroup",type="decimal",precision=3, scale=1,nullable=true)
     */
    private $nmSerogroup;

    public function getLab()
    {
        return $this->lab;
    }

    public function getPathogenIdentifierMethod()
    {
        return $this->pathogenIdentifierMethod;
    }

    public function getPathogenIdentifierOther()
    {
        return $this->pathogenIdentifierOther;
    }

    public function getSerotypeIdentifier()
    {
        return $this->serotypeIdentifier;
    }

    public function getSerotypeIdentifierOther()
    {
        return $this->serotypeIdentifierOther;
    }

    public function getLytA()
    {
        return $this->lytA;
    }

    public function getSodC()
    {
        return $this->sodC;
    }

    public function getHpd()
    {
        return $this->hpd;
    }

    public function getRNaseP()
    {
        return $this->rNaseP;
    }

    public function getSpnSerotype()
    {
        return $this->spnSerotype;
    }

    public function getHiSerotype()
    {
        return $this->hiSerotype;
    }

    public function getNmSerogroup()
    {
        return $this->nmSerogroup;
    }

    public function setLab(ExternalLab $lab)
    {
        $this->lab = $lab;
    }

    public function setPathogenIdentifierMethod(PathogenIdentifier $pathogenIdentifierMethod)
    {
        $this->pathogenIdentifierMethod = $pathogenIdentifierMethod;
    }

    public function setPathogenIdentifierOther($pathogenIdentifierOther)
    {
        $this->pathogenIdentifierOther = $pathogenIdentifierOther;
    }

    public function setSerotypeIdentifier(SerotypeIdentifier $serotypeIdentifier)
    {
        $this->serotypeIdentifier = $serotypeIdentifier;
    }

    public function setSerotypeIdentifierOther($serotypeIdentifierOther)
    {
        $this->serotypeIdentifierOther = $serotypeIdentifierOther;
    }

    public function setLytA($lytA)
    {
        $this->lytA = $lytA;
    }

    public function setSodC($sodC)
    {
        $this->sodC = $sodC;
    }

    public function setHpd($hpd)
    {
        $this->hpd = $hpd;
    }

    public function setRNaseP($rNaseP)
    {
        $this->rNaseP = $rNaseP;
    }

    public function setSpnSerotype($spnSerotype)
    {
        $this->spnSerotype = $spnSerotype;
    }

    public function setHiSerotype($hiSerotype)
    {
        $this->hiSerotype = $hiSerotype;
    }

    public function setNmSerogroup($nmSerogroup)
    {
        $this->nmSerogroup = $nmSerogroup;
    }


}
