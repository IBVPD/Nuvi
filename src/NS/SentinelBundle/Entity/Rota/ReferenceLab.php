<?php

namespace NS\SentinelBundle\Entity\Rota;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ReferenceLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Rota\ReferenceLabRepository")
 * @ORM\Table(name="rota_reference_labs")
 */
class ReferenceLab extends ExternalLab
{
    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\RotaVirus",inversedBy="referenceLab")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $caseFile;

    /**
     * @var \NS\SentinelBundle\Entity\ReferenceLab $lab
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\ReferenceLab",inversedBy="rotaCases")
     */
    private $lab;

    /**
     * @var string
     */
    private $type = 'RRL';

    /**
     * @return \NS\SentinelBundle\Entity\ReferenceLab
     */
    public function getLab()
    {
        return $this->lab;
    }

    /**
     * @param \NS\SentinelBundle\Entity\ReferenceLab $lab
     * @return $this
     */
    public function setLab(\NS\SentinelBundle\Entity\ReferenceLab $lab)
    {
        $this->lab = $lab;
        return $this;
    }

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
