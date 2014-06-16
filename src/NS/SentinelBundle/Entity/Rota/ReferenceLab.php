<?php

namespace NS\SentinelBundle\Entity\Rota;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ReferenceLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Rota\ReferenceLab")
 */
class ReferenceLab extends ExternalLab
{
    protected $caseClass = 'NS\SentinelBundle\Entity\RotaVirus';
}