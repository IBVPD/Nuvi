<?php

namespace NS\SentinelBundle\Entity\Pneumonia;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\ReferenceLab as RegionalReferenceLab;
use NS\SentinelBundle\Entity\ReferenceLabResultInterface;

/**
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Pneumonia\ReferenceLabRepository")
 * @ORM\Table(name="pneu_reference_labs")
 * @ORM\EntityListeners(value={"NS\SentinelBundle\Entity\Listener\BaseExternalLabListener"})
 */
class ReferenceLab extends ExternalLab implements ReferenceLabResultInterface
{
    /**
     * @var BaseCase|Pneumonia|null
     *
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Pneumonia\Pneumonia",inversedBy="referenceLab")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     * @ORM\Id
     */
    protected $caseFile;

    /**
     * @var RegionalReferenceLab $lab
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\ReferenceLab",inversedBy="ibdCases")
     * @ORM\JoinColumn(name="rrl_id")
     */
    private $lab;

    /** @var string */
    private $type = 'RRL';

    /**
     * @return RegionalReferenceLab
     */
    public function getLab(): ?RegionalReferenceLab
    {
        return $this->lab;
    }

    public function setLab(RegionalReferenceLab $lab): void
    {
        $this->lab = $lab;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setCaseFile(BaseCase $caseFile = null): void
    {
        $this->caseFile = $caseFile;
    }
}
