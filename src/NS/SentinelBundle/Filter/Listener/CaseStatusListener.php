<?php

namespace NS\SentinelBundle\Filter\Listener;

use Lexik\Bundle\FormFilterBundle\Event\GetFilterConditionEvent;
use NS\SentinelBundle\Form\Types\CaseStatus;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
        return [
            // if a Doctrine\ORM\QueryBuilder is passed to the lexik_form_filter.query_builder_updater service
            'lexik_form_filter.apply.orm.filter.status' => ['filterCaseStatus'],
        ];
    }

    /**
     * Apply a filter for a filter_locale type.
     *
     * This method should work with both ORM and DBAL query builder.
     * @param GetFilterConditionEvent $event
     */
    public function filterCaseStatus(GetFilterConditionEvent $event)
    {
        $values = $event->getValues();

        if ($values['value'] instanceof CaseStatus && $values['value']->getValue() >= 0) {
            $paramName = str_replace('.', '_', $event->getField());
            $qb        = $event->getQueryBuilder();

            $qb->andWhere($event->getField().' = :'.$paramName)
               ->setParameter($paramName, $values['value']->getValue());
        }
    }
}
