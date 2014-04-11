<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\Types\SerotypeIdentifier;
use NS\SentinelBundle\Form\Types\SampleType;
use NS\SentinelBundle\Form\Types\Volume;
use NS\SentinelBundle\Form\Types\IsolateType;

// Annotations
use Gedmo\Mapping\Annotation as Gedmo;
use \NS\SecurityBundle\Annotation\Secured;
use \NS\SecurityBundle\Annotation\SecuredCondition;

/**
 * Description of ReferenceLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\NationalLab")
 */
class NationalLab extends BaseLab
{

}