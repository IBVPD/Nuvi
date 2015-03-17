<?php

namespace NS\SentinelBundle\Entity\Rota;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ReferenceLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Rota\ReferenceLabRepository")
 */
class ReferenceLab extends ExternalLab
{
    protected $caseClass = 'NS\SentinelBundle\Entity\RotaVirus';

    /**
     * @var NS\SentinelBundle\Entity\ReferenceLab $lab
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\ReferenceLab",inversedBy="rotaCases")
     */
    private $lab;

    private $type = 'RRL';

    public function getLab()
    {
        return $this->lab;
    }

    public function setLab(\NS\SentinelBundle\Entity\ReferenceLab $lab)
    {
        $this->lab = $lab;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}