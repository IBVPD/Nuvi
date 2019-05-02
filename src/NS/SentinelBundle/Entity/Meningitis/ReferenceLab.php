<?php

namespace NS\SentinelBundle\Entity\Meningitis;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Entity\ReferenceLab as RegionalReferenceLab;
use NS\SentinelBundle\Entity\ReferenceLabResultInterface;

/**
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Meningitis\ReferenceLabRepository")
 * @ORM\Table(name="mening_reference_labs")
 * @ORM\EntityListeners(value={"NS\SentinelBundle\Entity\Listener\BaseExternalLabListener"})
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
     * @var RegionalReferenceLab|null
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\ReferenceLab",inversedBy="ibdCases")
     * @ORM\JoinColumn(name="rrl_id")
     */
    private $lab;

    /** @var string */
    private $type = 'RRL';

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
}
