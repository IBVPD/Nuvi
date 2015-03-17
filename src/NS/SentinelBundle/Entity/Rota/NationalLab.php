<?php

namespace NS\SentinelBundle\Entity\Rota;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ReferenceLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Rota\NationalLabRepository")
 */
class NationalLab extends ExternalLab
{
    protected $caseClass = 'NS\SentinelBundle\Entity\RotaVirus';

    private $type = 'NL';

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