<?php

namespace NS\SentinelBundle\Entity\IBD;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ReferenceLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\IBD\NationalLab")
 */
class NationalLab extends ExternalLab
{
    protected $caseClass = 'NS\SentinelBundle\Entity\IBD';

}