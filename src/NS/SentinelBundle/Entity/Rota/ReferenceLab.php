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

    /**
     * @var NS\SentinelBundle\Entity\ReferenceLab $lab
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\ReferenceLab",inversedBy="rotaCases")
     */
    private $lab;

    public function getLab()
    {
        return $this->lab;
    }

    public function setLab(\NS\SentinelBundle\Entity\ReferenceLab $lab)
    {
        $this->lab = $lab;
        return $this;
    }
}
