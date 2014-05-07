<?php

namespace NS\SentinelBundle\Entity\Rota;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ExternalLab
 *
 * @ORM\Entity()
 * @ORM\Table(name="rota_external_labs",uniqueConstraints={@ORM\UniqueConstraint(name="site_type_idx",columns={"case_id","discr"})})
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr",type="string")
 * @ORM\DiscriminatorMap({"reference" = "ReferenceLab", "national" = "NationalLab"})
 * @author gnat
 */
class ExternalLab
{
    /**
     * @var integer $id
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\RotaVirus",inversedBy="externalLabs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $case;

    /**
     * @var string $name
     * @ORM\Column(name="name",type="string")
     */
    protected $name;
}
