<?php

namespace NS\SentinelBundle\Filter\Listener;

use Lexik\Bundle\FormFilterBundle\Event\GetFilterConditionEvent;
use NS\SentinelBundle\Form\Types\CaseStatus;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CaseStatusListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            // if a Doctrine\ORM\QueryBuilder is passed to the lexik_form_filter.query_builder_updater service
            'lexik_form_filter.apply.orm.filter.status' => ['filterCaseStatus'],
        ];
    }

    public function filterCaseStatus(GetFilterConditionEvent $event): void
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
