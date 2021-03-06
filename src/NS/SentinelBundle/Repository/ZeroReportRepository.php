<?php

namespace NS\SentinelBundle\Repository;

use DateTime;
use NS\SecurityBundle\Doctrine\SecuredEntityRepository;

class ZeroReportRepository extends SecuredEntityRepository
{
    public function getExistingReports($type, DateTime $from, DateTime $to)
    {
        return $this->secure(
                $this->createQueryBuilder('z')
                ->innerJoin('z.site', 's')
                ->where('z.caseType = :caseType AND z.yearMonth >= :start AND z.yearMonth <= :end')
                ->setParameters([
                    'caseType' => $type,
                    'start' => $from->format('Ym'),
                    'end' => $to->format('Ym'),
                ])
            )
            ->getQuery()
            ->getResult();
    }
}
