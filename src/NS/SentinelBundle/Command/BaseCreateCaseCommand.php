<?php

namespace NS\SentinelBundle\Command;

use \NS\SentinelBundle\Repository\Site;
use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * Description of BaseCreateCaseCommand
 *
 * @author gnat
 */
class BaseCreateCaseCommand extends ContainerAwareCommand
{
    public function getCaseId(Site $site)
    {
        return md5(uniqid() . spl_object_hash($site) . time());
    }

    public function getRandomDate(\DateTime $before = null, \DateTime $after = null)
    {
        $years  = range(1995, date('Y'));
        $months = range(1, 12);
        $days   = range(1, 28);

        $yKey = array_rand($years);
        $mKey = array_rand($months);
        $dKey = array_rand($days);

        if ($before != null)
        {
            $byear = $before->format('Y');
            while ($years[$yKey] > $byear)
                $yKey  = array_rand($years);
        }

        if ($after != null)
        {
            $ayear = $after->format('Y');
            while ($years[$yKey] < $ayear)
            {
                $yKey = array_rand($years);
            }
        }

        return new \DateTime("{$years[$yKey]}-{$months[$mKey]}-{$days[$dKey]}");
    }
}