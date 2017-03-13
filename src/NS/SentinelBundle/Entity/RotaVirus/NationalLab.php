<?php

namespace NS\SentinelBundle\Entity\RotaVirus;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Validators as LocalAssert;

/**
 * Description of ReferenceLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\RotaVirus\NationalLabRepository")
 * @ORM\Table(name="rota_national_labs")
 *
 * @LocalAssert\GreaterThanDate(lessThanField="caseFile.siteLab.stoolSentToNLDate",greaterThanField="dateReceived",message="form.validation.vaccination-after-admission")
 * @LocalAssert\GreaterThanDate(lessThanField="dateReceived",greaterThanField="genotypingDate",message="form.validation.vaccination-after-admission")
 */
class NationalLab extends ExternalLab
{
    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\RotaVirus",inversedBy="nationalLab")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     * @ORM\Id
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
