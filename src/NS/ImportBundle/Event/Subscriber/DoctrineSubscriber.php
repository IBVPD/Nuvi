<?php

namespace NS\ImportBundle\Event\Subscriber;

use \Lexik\Bundle\FormFilterBundle\Event\ApplyFilterEvent;
use \Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Description of DoctrineSubscriber
 *
 * @author gnat
 */
class DoctrineSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     * @return array
     */
    public static function getSubscribedEvents()
    {
        $dateRange = array('filterDateRange');
        return array(
            // Doctrine ORM - filter field types
            'lexik_form_filter.apply.orm.IBDReportFilterType.admDate'           => $dateRange,
            'lexik_form_filter.apply.orm.IBDReportFilterType.createdDate'       => $dateRange,
            'lexik_form_filter.apply.orm.RotaVirusReportFilterType.admDate'     => $dateRange,
            'lexik_form_filter.apply.orm.RotaVirusReportFilterType.createdDate' => $dateRange,
        );
    }

    /**
     * @param ApplyFilterEvent $event
     */
    public function filterDateRange(ApplyFilterEvent $event)
    {
        $qb     = $event->getQueryBuilder();
        $expr   = $event->getFilterQuery()->getExpressionBuilder();
        $values = $event->getValues();
        $value  = $values['value'];

        if (isset($value['left_date'][0]) || isset($value['right_date'][0])) {
            $qb->andWhere($expr->dateInRange($event->getField(), $value['left_date'][0], $value['right_date'][0]));
        }
    }
}
