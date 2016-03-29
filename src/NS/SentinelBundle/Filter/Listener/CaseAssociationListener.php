<?php

namespace NS\SentinelBundle\Filter\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\FormFilterBundle\Event\ApplyFilterEvent;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Collections\Collection;

/**
 * Description of Listener
 *
 * @author gnat
 */
class CaseAssociationListener implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'lexik_form_filter.apply.orm.ibd_filter_form.country'       => array('filterObject'),
            'lexik_form_filter.apply.orm.ibd_filter_form.region'        => array('filterObject'),
            'lexik_form_filter.apply.orm.ibd_filter_form.site'          => array('filterObject'),
            'lexik_form_filter.apply.orm.rotavirus_filter_form.country' => array('filterObject'),
            'lexik_form_filter.apply.orm.rotavirus_filter_form.region'  => array('filterObject'),
            'lexik_form_filter.apply.orm.rotavirus_filter_form.site'    => array('filterObject'),
        );
    }

    /**
     * @param ApplyFilterEvent $event
     * @throws \Exception
     */
    public function filterObject(ApplyFilterEvent $event)
    {
        $queryBuilder = $event->getQueryBuilder();
        if (!$queryBuilder instanceof QueryBuilder) {
            return;
        }

        $expr   = $event->getFilterQuery()->getExpr();
        $values = $event->getValues();

        if (is_object($values['value'])) {
            if ($values['value'] instanceof Collection) {
                $ids = array();

                foreach ($values['value'] as $value) {
                    if (!is_callable(array($value, 'getId'))) {
                        throw new \Exception(sprintf('Can\'t call method "getId()" on an instance of "%s"', get_class($value)));
                    }

                    $ids[] = $value->getId();
                }

                if (count($ids) > 0) {
                    $queryBuilder->andWhere($expr->in($event->getField(), $ids));
                }
            } else {
                if (!is_callable(array($values['value'], 'getId'))) {
                    throw new \Exception(sprintf('Can\'t call method "getId()" on an instance of "%s"', get_class($values['value'])));
                }

                $fieldAlias = 'p_' . substr($event->getField(), strpos($event->getField(), '.') + 1);

                $queryBuilder->andWhere($expr->eq($event->getField(), ":$fieldAlias"));
                $queryBuilder->setParameter($fieldAlias, $values['value']->getId());
            }
        }
    }
}
