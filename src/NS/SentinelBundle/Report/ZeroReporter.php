<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 19/05/16
 * Time: 4:38 PM
 */

namespace NS\SentinelBundle\Report;

use DateTime;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use NS\SentinelBundle\Entity\ZeroReport;
use NS\SentinelBundle\Report\Result\ZeroReportResult;

class ZeroReporter
{
    /**
     * @var EntityManagerInterface
     */
    private $entityMgr;

    /** @var array */
    private $zeroReports = [];

    /**
     * ZeroReporter constructor.
     * @param EntityManagerInterface $entityMgr
     */
    public function __construct(EntityManagerInterface $entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    /**
     * @param string $type
     * @param DateTime $from
     * @param DateTime $to
     * @return ZeroReportResult
     */
    public function getZeroReports($type, DateTime $from, DateTime $to)
    {
        $result = new ZeroReportResult($type, $from, $to);
        $casesPerMonth = $this->entityMgr->getRepository($type)->getCasesPerMonth($from, $to);
        foreach ($casesPerMonth as $index => $cases) {
            $result->addCaseCount($cases[0]->getSite(), $cases['theYear'], $cases['theMonth'], $cases['caseCount']);
        }

        $this->zeroReports = $this->entityMgr->getRepository('NSSentinelBundle:ZeroReport')->getExistingReports($type, $from, $to);
        foreach ($this->zeroReports as $report) {
            $result->addZeroReport($report->getSite(), $report->getYear(), $report->getMonth(), $report->getType());
        }

        return $result;
    }

    /**
     * @param array $submittedData
     * @param array $data
     */
    public function updateZeroReports(array $submittedData, array $data)
    {
        try {
            $this->entityMgr->beginTransaction();
            $reports = $this->entityMgr->getRepository('NSSentinelBundle:ZeroReport')->getExistingReports($data['type'], $data['from'], $data['to']);
            foreach ($reports as $report) {
                $this->entityMgr->remove($report);
            }

            foreach ($submittedData as $siteCode => $zeroReport) {
                $site = $this->entityMgr->getReference('NS\SentinelBundle\Entity\Site', $siteCode);

                foreach ($zeroReport as $date => $value) {
                    if ($value == 'zero' || $value == 'non') {
                        list($year, $month) = explode('-', $date);
                        $report = new ZeroReport($site, $data['type'], $value, $month, $year);
                        $this->entityMgr->persist($report);
                    }
                }
            }

            $this->entityMgr->flush();
            $this->entityMgr->commit();
        } catch (DBALException $exception) {
            $this->entityMgr->rollback();
        }
    }
}
