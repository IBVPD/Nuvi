<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 02/03/17
 * Time: 3:10 PM
 */

namespace NS\SentinelBundle\Filter\Listener;

use Lexik\Bundle\FormFilterBundle\Event\GetFilterConditionEvent;
use NS\SentinelBundle\Form\IBD\Types\CaseResult;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CaseResultListener implements EventSubscriberInterface
{
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            // if a Doctrine\ORM\QueryBuilder is passed to the lexik_form_filter.query_builder_updater service
            'lexik_form_filter.apply.orm.filter.result' => ['applyFilter'],
        ];
    }

    /**
     * @param GetFilterConditionEvent $event
     */
    public function applyFilter(GetFilterConditionEvent $event)
    {
        $values = $event->getValues();

        if ($values['value'] instanceof CaseResult) {
            if ($values['value']->getValue() >= 0) {
                $paramName = str_replace('.', '_', $event->getField());
                $qb        = $event->getQueryBuilder();

                $qb->andWhere($event->getField().' = :'.$paramName)
                    ->setParameter($paramName, $values['value']->getValue());
            }
        }
    }
}
