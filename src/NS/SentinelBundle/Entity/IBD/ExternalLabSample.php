<?php

namespace NS\SentinelBundle\Entity\IBD;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Form\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\Types\SerotypeIdentifier;
use NS\SentinelBundle\Form\Types\SampleType;
use NS\SentinelBundle\Form\Types\SpnSerotype;
use NS\SentinelBundle\Form\Types\HiSerotype;
use NS\SentinelBundle\Form\Types\NmSerogroup;

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Description of ExternalLabSample
 *
 * @author gnat
 * 
 * @ORM\Entity()
 * @ORM\Table(name="ibd_external_lab_samples")

 * @ORM\HasLifecycleCallbacks * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr",type="string")
 * @ORM\DiscriminatorMap({
 *                          "CSF"        = "CSFLabSample",
 *                          "ISOLATE"    = "IsolateLabSample",
 *                          "WHOLE"      = "WholeLabSample",
 *                          "BROTH"      = "BrothLabSample",
 *                          "PLEURAL"    = "PleuralLabSample",
 *                          "INOCULATED" = "InoculatedLabSample"
 * })
 * @Gedmo\Loggable
 */
class ExternalLabSample implements \Serializable
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
     * @ORM\Column(name="spnSerotype",type="SpnSerotype",nullable=true)
     */
    private $spnSerotype;

    /**
     * @var string $spnSerotypeOther
     * @ORM\Column(name="spnSerotypeOther",type="string",nullable=true)
     */
    private $spnSerotypeOther;

    /**
     * @var double
     * @ORM\Column(name="hiSerotype",type="HiSerotype",nullable=true)
     */
    private $hiSerotype;

    /**
     * @var string $hiSerotypeOther
     * @ORM\Column(name="hiSerotypeOther",type="string",nullable=true)
     */
    private $hiSerotypeOther;

    /**
     * @var double
     * @ORM\Column(name="nmSerogroup",type="NmSerogroup",nullable=true)
     */
    private $nmSerogroup;

    /**
     * @var string $nmSerogroupOther
     * @ORM\Column(name="nmSerogroupOther",type="string",nullable=true)
     */
    private $nmSerogroupOther;

    private $type;

    public function getId()
    {
        return $this->id;
    }

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

    public function getType()
    {
        return $this->getTypeByClass();
    }

    public function getTypeByClass()
    {
        if($this->type)
            return $this->type;

        $sample = new SampleType();
        $values = $sample->getValues();
        switch(get_class($this))
        {
            case 'NS\SentinelBundle\Entity\IBD\CSFLabSample':
                $this->type = $values[SampleType::CSF];
                break;
            case 'NS\SentinelBundle\Entity\IBD\BrothLabSample':
                $this->type = $values[SampleType::BROTH];
                break;
            case 'NS\SentinelBundle\Entity\IBD\InoculatedLabSample':
                $this->type = $values[SampleType::INOCULATED];
                break;
            case 'NS\SentinelBundle\Entity\IBD\IsolateLabSample':
                $this->type = $values[SampleType::ISOLATE];
                break;
            case 'NS\SentinelBundle\Entity\IBD\PleuralLabSample':
                $this->type = $values[SampleType::PLEURAL];
                break;
            case 'NS\SentinelBundle\Entity\IBD\WholeLabSample':
                $this->type = $values[SampleType::WHOLE];
                break;
            default:
                return 'ERROR TYPE!';
        }

        return $this->type;
    }

    public function getSpnSerotypeOther()
    {
        return $this->spnSerotypeOther;
    }

    public function getHiSerotypeOther()
    {
        return $this->hiSerotypeOther;
    }

    public function getNmSerogroupOther()
    {
        return $this->nmSerogroupOther;
    }

    public function setSpnSerotypeOther($spnSerotypeOther)
    {
        $this->spnSerotypeOther = $spnSerotypeOther;
    }

    public function setHiSerotypeOther($hiSerotypeOther)
    {
        $this->hiSerotypeOther = $hiSerotypeOther;
    }

    public function setNmSerogroupOther($nmSerogroupOther)
    {
        $this->nmSerogroupOther = $nmSerogroupOther;
    }

    public function setType($type)
    {
        $this->type = $type;
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

    public function validate(ExecutionContextInterface $context)
    {
        // if pathogenIdentifierMethod is other, enforce value in 'pathogenIdentifierMethod other' field
        if($this->pathogenIdentifierMethod && $this->pathogenIdentifierMethod->equal(PathogenIdentifier::OTHER) && empty($this->pathogenIdentifierOther))
            $context->addViolationAt('pathogenIdentifierMethod',"form.validation.pathogenIdentifierMethod-other-without-other-text");

        // if serotypeIdentifier is other, enforce value in 'serotypeIdentifier other' field
        if($this->serotypeIdentifier && $this->serotypeIdentifier->equal(SerotypeIdentifier::OTHER) && empty($this->serotypeIdentifierOther))
            $context->addViolationAt('serotypeIdentifier',"form.validation.serotypeIdentifier-other-without-other-text");

        if($this->spnSerotype && $this->spnSerotype->equal(SpnSerotype::OTHER) && empty($this->spnSerotypeOther))
            $context->addViolationAt('spnSerotype',"form.validation.spnSerotype-other-without-other-text");

        if($this->hiSerotype && $this->hiSerotype->equal(HiSerotype::OTHER) && empty($this->hiSerotypeOther))
            $context->addViolationAt('hiSerotype',"form.validation.hiSerotype-other-without-other-text");

        if($this->nmSerogroup && $this->nmSerogroup->equal(NmSerogroup::OTHER) && empty($this->nmSerogroupOther))
            $context->addViolationAt('nmSerogroup',"form.validation.nmSerogroup-other-without-other-text");
    }

    public function getIncompleteField()
    {
        $ret = parent::getIncompleteField();
        if($ret)
            return $ret;

        if($this->pathogenIdentifierMethod && $this->pathogenIdentifierMethod->equal(PathogenIdentifier::OTHER) && empty($this->pathogenIdentifierOther))
            return 'pathogenIdentier';

        if($this->serotypeIdentifierMethod && $this->serotypeIdentifierMethod->equal(SerotypeIdentifier::OTHER) && empty($this->serotypeIdentifierOther))
            return 'serotypeIdentier';

        if($this->spnSerotype && $this->spnSerotype->equal(SpnSerotype::OTHER) && empty($this->spnSerotypeOther))
            return 'spnSerotype';

        if($this->hiSerotype && $this->hiSerotype->equal(HiSerotype::OTHER) && empty($this->hiSerotypeOther))
            return 'hiSerotype';

        if($this->nmSerogroup && $this->nmSerogroup->equal(NmSerogroup::OTHER) && empty($this->nmSerogroupOther))
            return 'nmSerogroup';
    }

    public function getChildInstance()
    {
        if($this->type)
        {
            if($this instanceof CSFLabSample || $this instanceof IsolateLabSample || $this instanceof WholeLabSample || $this instanceof BrothLabSample || $this instanceof PleuralLabSample || $this instanceof InoculatedLabSample )
                return $this;

            $obj        = null;
            $sampleType = new SampleType();
            $type       = $sampleType->getIndexForValue($this->type);
            switch($type)
            {
                case SampleType::CSF:
                    $obj = new CSFLabSample();
                    break;
                case SampleType::ISOLATE:
                    $obj = new IsolateLabSample();
                    break;
                case SampleType::WHOLE:
                    $obj = new WholeLabSample();
                    break;
                case SampleType::BROTH:
                    $obj = new BrothLabSample();
                    break;
                case SampleType::PLEURAL:
                    $obj = new PleuralLabSample();
                    break;
                case SampleType::INOCULATED:
                    $obj = new InoculatedLabSample();
                    break;
            }

            if($obj)
            {
                $obj->unserialize($this->serialize());
                $obj->setLab($this->lab);
                return $obj;
            }
            else
                throw new \RuntimeException("Unable to figure out ".$this->type." ".print_r($sampleType->getValueArray(),true));
        }

        return $this;
    }

    public function serialize()
    {
        return serialize(array(
                            $this->id,
                            $this->pathogenIdentifierMethod,
                            $this->pathogenIdentifierOther,
                            $this->serotypeIdentifier,
                            $this->serotypeIdentifierOther,
                            $this->lytA,
                            $this->sodC,
                            $this->hpd,
                            $this->rNaseP,
                            $this->spnSerotype,
                            $this->hiSerotype,
                            $this->nmSerogroup
                        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->pathogenIdentifierMethod,
            $this->pathogenIdentifierOther,
            $this->serotypeIdentifier,
            $this->serotypeIdentifierOther,
            $this->lytA,
            $this->sodC,
            $this->hpd,
            $this->rNaseP,
            $this->spnSerotype,
            $this->hiSerotype,
            $this->nmSerogroup) = unserialize($serialized);
    }
}
