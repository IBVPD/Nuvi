<?php

namespace NS\SentinelBundle\Entity\IBD;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ReferenceLab
 * @author gnat
 *
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\IBD\NationalLabRepository")
 * @ORM\Table(name="ibd_national_labs")
 * @ORM\HasLifecycleCallbacks
 */
class NationalLab extends ExternalLab
{
    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\IBD",inversedBy="nationalLab")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Id
     */
    protected $caseFile;

    /**
     * @var string
     */
    private $type        = 'NL';

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
     * @return NationalLab
     */
    public function setCaseFile($caseFile = null)
    {
        $this->caseFile = $caseFile;
        return $this;
    }
}
