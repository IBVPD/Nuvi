<?php

namespace NS\SentinelBundle\Filter\Listener;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Lexik\Bundle\FormFilterBundle\Event\GetFilterConditionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CaseAssociationListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_form_filter.apply.orm.ibd_filter_form.country'       => ['filterObject'],
            'lexik_form_filter.apply.orm.ibd_filter_form.region'        => ['filterObject'],
            'lexik_form_filter.apply.orm.ibd_filter_form.site'          => ['filterObject'],
            'lexik_form_filter.apply.orm.rotavirus_filter_form.country' => ['filterObject'],
            'lexik_form_filter.apply.orm.rotavirus_filter_form.region'  => ['filterObject'],
            'lexik_form_filter.apply.orm.rotavirus_filter_form.site'    => ['filterObject'],
        ];
    }

    public function filterObject(GetFilterConditionEvent $event): void
    {
        $queryBuilder = $event->getQueryBuilder();
        if (!$queryBuilder instanceof QueryBuilder) {
            return;
        }

        $expr   = $event->getFilterQuery()->getExpr();
        $values = $event->getValues();

        if (is_object($values['value'])) {
            if ($values['value'] instanceof Collection) {
                $ids = [];

                foreach ($values['value'] as $value) {
                    if (!is_callable([$value, 'getId'])) {
                        throw new Exception(sprintf('Can\'t call method "getId()" on an instance of "%s"', get_class($value)));
                    }

                    $ids[] = $value->getId();
                }

                if (count($ids) > 0) {
                    $queryBuilder->andWhere($expr->in($event->getField(), $ids));
                }
            } else {
                if (!is_callable([$values['value'], 'getId'])) {
                    throw new Exception(sprintf('Can\'t call method "getId()" on an instance of "%s"', get_class($values['value'])));
                }

                $fieldAlias = 'p_' . substr($event->getField(), strpos($event->getField(), '.') + 1);

                $queryBuilder->andWhere($expr->eq($event->getField(), ":$fieldAlias"));
                $queryBuilder->setParameter($fieldAlias, $values['value']->getId());
            }
        }
    }
}
