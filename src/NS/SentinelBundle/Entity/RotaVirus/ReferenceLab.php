<?php

namespace NS\SentinelBundle\Entity\RotaVirus;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Entity\ReferenceLab as RegionalReferenceLab;
use NS\SentinelBundle\Entity\ReferenceLabResultInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\RotaVirus\ReferenceLabRepository")
 * @ORM\Table(name="rota_reference_labs")
 * @ORM\EntityListeners(value={"NS\SentinelBundle\Entity\Listener\BaseExternalLabListener"})
 */
class ReferenceLab extends ExternalLab implements ReferenceLabResultInterface
{
    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\RotaVirus",inversedBy="referenceLab")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     * @ORM\Id
     */
    protected $caseFile;

    /**
     * @var RegionalReferenceLab|null
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\ReferenceLab",inversedBy="rotaCases")
     * @ORM\JoinColumn(name="rrl_id")
     * @Assert\NotBlank(groups={"Completeness"})
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
