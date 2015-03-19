<?php

namespace NS\SentinelBundle\Filter;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\FormFilterBundle\Event\ApplyFilterEvent;
use NS\SentinelBundle\Form\Types\CaseStatus;

/**
 * Description of CaseStatusListener
 *
 * @author gnat
 */
class CaseStatusListener implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            // if a Doctrine\ORM\QueryBuilder is passed to the lexik_form_filter.query_builder_updater service
            'lexik_form_filter.apply.orm.CaseStatus' => array('filterCaseStatus'),

            // if a Doctrine\DBAL\Query\QueryBuilder is passed to the lexik_form_filter.query_builder_updater service
            'lexik_form_filter.apply.dbal.CaseStatus' => array('filterCaseStatus'),
        );
    }

    /**
     * Apply a filter for a filter_locale type.
     *
     * This method should work with both ORM and DBAL query builder.
     */
    public function filterCaseStatus(ApplyFilterEvent $event)
    {
        $values = $event->getValues();

        if ($values['value'] instanceof CaseStatus && $values['value']->getValue() >= 0)
        {
            $paramName = str_replace('.', '_', $event->getField());
            $qb        = $event->getQueryBuilder();

            $qb->andWhere($event->getField().' = :'.$paramName)
               ->setParameter($paramName, $values['value']->getValue());
        }
    }
}