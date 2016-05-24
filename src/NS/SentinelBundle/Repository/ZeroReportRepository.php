<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 23/05/16
 * Time: 1:27 PM
 */

namespace NS\SentinelBundle\Repository;

use NS\SecurityBundle\Doctrine\SecuredEntityRepository;

class ZeroReportRepository extends SecuredEntityRepository
{
    public function getExistingReports($type, \DateTime $from, \DateTime $to)
    {
        return $this->secure(
                $this->createQueryBuilder('z')
                ->innerJoin('z.site', 's')
                ->where('z.caseType = :caseType AND z.yearMonth >= :start AND z.yearMonth <= :end')
                ->setParameters(array(
                    'caseType' => $type,
                    'start' => $from->format('Ym'),
                    'end' => $to->format('Ym'),
                ))
            )
            ->getQuery()
            ->getResult();
    }
}
