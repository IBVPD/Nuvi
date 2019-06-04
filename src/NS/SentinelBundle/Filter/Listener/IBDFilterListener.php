<?php

namespace NS\SentinelBundle\Filter\Listener;

use Lexik\Bundle\FormFilterBundle\Event\GetFilterConditionEvent;
use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class IBDFilterListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_form_filter.apply.orm.filter.adm_dx'                  => ['applyFilter'],
            'lexik_form_filter.apply.orm.filter.disch_dx'               => ['applyFilter'],
            'lexik_form_filter.apply.orm.filter.disch_class'            => ['applyFilter'],
//            'lexik_form_filter.apply.orm.rotavirus_filter_form.country' => ['filterObject'],
//            'lexik_form_filter.apply.orm.rotavirus_filter_form.region'  => ['filterObject'],
//            'lexik_form_filter.apply.orm.rotavirus_filter_form.site'    => ['filterObject'],
        ];
    }

    public function applyFilter(GetFilterConditionEvent $event): void
    {
        $values = $event->getValues();

        if ($values['value'] instanceof ArrayChoice) {
            if ($values['value']->getValue() >= 0) {
                $paramName = str_replace('.', '_', $event->getField());
                $qb        = $event->getQueryBuilder();

                $qb->andWhere($event->getField().' = :'.$paramName)
                    ->setParameter($paramName, $values['value']->getValue());
            }
        }
    }
}
