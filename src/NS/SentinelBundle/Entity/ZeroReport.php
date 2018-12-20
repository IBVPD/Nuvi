<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 19/05/16
 * Time: 3:49 PM
 */

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use NS\SecurityBundle\Annotation as Security;

/**
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\ZeroReportRepository")
 * @ORM\Table(name="zero_reports")
 *
 * @Security\Secured(conditions={
 *      @Security\SecuredCondition(roles={"ROLE_REGION"},through={"site","country"},relation="region",class="NSSentinelBundle:Region"),
 *      @Security\SecuredCondition(roles={"ROLE_COUNTRY"},through={"site"},relation="country",class="NSSentinelBundle:Country"),
 *      @Security\SecuredCondition(roles={"ROLE_SITE"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 *
 */
class ZeroReport
{
    /**
     * @var int
     * @ORM\Column(name="id",type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(name="yearMonth",type="integer")
     */
    private $yearMonth;

    private $year;
    private $month;

    /**
     * @var string
     * @ORM\Column(name="type",type="string")
     */
    private $type;

    /**
     * @var int
     * @ORM\Column(name="caseType",type="string")
     * @Assert\Choice(choices={"Pneumonia","Meningitis","IBD","RotaVirus"})
     */
    private $caseType;

    /**
     * @var Site
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\Site", inversedBy="zeroReports")
     * @ORM\JoinColumn(referencedColumnName="code")
     */
    private $site;

    /**
     * ZeroReport constructor.
     *
     * @param Site   $site
     * @param int    $caseType
     * @param string $type
     * @param int    $month
     * @param int    $year
     */
    public function __construct(Site $site, $caseType, $type, $month, $year)
    {
        if ($year < 2000) {
            throw new \InvalidArgumentException('Expecting a year after 2000');
        }

        if (!is_numeric($month) || $month <= 0 || $month > 12) {
            throw new \InvalidArgumentException('Expecting a month between 1 and 12');
        }

        $this->site      = $site;
        $this->caseType  = $caseType;
        $this->type      = $type;
        $this->yearMonth = sprintf('%s%s', $year, (is_numeric($month) && $month < 10) ? str_pad($month, 2, '0', STR_PAD_LEFT) : $month);
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    public function getYear(): int
    {
        if (!$this->year) {
            $this->year  = substr($this->yearMonth, 0, -2);
            $this->month = substr($this->yearMonth, -2);
        }

        return $this->year;
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        if (!$this->month) {
            $this->year  = substr($this->yearMonth, 0, -2);
            $this->month = substr($this->yearMonth, -2);
        }

        return $this->month;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getCaseType()
    {
        return $this->caseType;
    }

    /**
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }
}
