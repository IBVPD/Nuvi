<?php

namespace NS\SentinelBundle\Entity\Rota;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ReferenceLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Rota\NationalLabRepository")
 * @ORM\Table(name="rota_national_labs")
 */
class NationalLab extends ExternalLab
{
    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\RotaVirus",inversedBy="nationalLab")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $caseFile;

    /**
     * @var string
     */
    private $type = 'NL';

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
     * @return mixed
     */
    public function getCaseFile()
    {
        return $this->caseFile;
    }

    /**
     * @param mixed $caseFile
     * @return $this|void
     */
    public function setCaseFile($caseFile = null)
    {
        $this->caseFile = $caseFile;
    }
}
