<?php

namespace NS\SentinelBundle\Entity\IBD;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Entity\ReferenceLab as RegionalReferenceLab;
use NS\SentinelBundle\Entity\ReferenceLabResultInterface;

/**
 * Description of ReferenceLab
 * @author gnat
 *
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\IBD\ReferenceLabRepository")
 * @ORM\Table(name="ibd_reference_labs")
 * @ORM\HasLifecycleCallbacks
 */
class ReferenceLab extends ExternalLab implements ReferenceLabResultInterface
{
    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\IBD",inversedBy="referenceLab")
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

    public function getLab(): ?RegionalReferenceLab
    {
        return $this->lab;
    }

    public function setLab(?RegionalReferenceLab $lab): void
    {
        $this->lab = $lab;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
