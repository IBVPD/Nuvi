<?php

namespace NS\SentinelBundle\Entity\Meningitis;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Entity\ReferenceLabResultInterface;

/**
 * Description of ReferenceLab
 * @author gnat
 *
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Meningitis\ReferenceLabRepository")
 * @ORM\Table(name="mening_reference_labs")
 * @ORM\HasLifecycleCallbacks
 */
class ReferenceLab extends ExternalLab implements ReferenceLabResultInterface
{
    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Meningitis\Meningitis",inversedBy="referenceLab")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     * @ORM\Id
     */
    protected $caseFile;

    /**
     * @var \NS\SentinelBundle\Entity\ReferenceLab $lab
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\ReferenceLab",inversedBy="ibdCases")
     * @ORM\JoinColumn(name="rrl_id")
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
     * @return ReferenceLab
     */
    public function setCaseFile($caseFile = null)
    {
        $this->caseFile = $caseFile;
        return $this;
    }
}
