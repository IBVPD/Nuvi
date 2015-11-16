<?php

namespace NS\SentinelBundle\Filter\Type;

use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use \NS\SecurityBundle\Role\ACLConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use \Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class BaseQuarterlyFilterType extends AbstractType
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var ACLConverter
     */
    private $converter;

    protected $fieldName = 'admDate';

    /**
     * @param SecurityContextInterface $securityContext
     * @param ACLConverter $converter
     */
    public function __construct(SecurityContextInterface $securityContext, ACLConverter $converter)
    {
        $this->securityContext = $securityContext;
        $this->converter       = $converter;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('year', 'filter_number', array('label' => 'report-filter-form.year','apply_filter'=>array($this,'filterYear')));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this,'preSetData'));
    }

    /**
     * @param QueryInterface $filterQuery
     * @param $field
     * @param $values
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function filterYear(QueryInterface $filterQuery, $field, $values)
    {
        if ($values['value'] > 0) {
            $queryBuilder = $filterQuery->getQueryBuilder();

            $config = $queryBuilder->getEntityManager()->getConfiguration();
            $config->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');

            $alias = $values['alias'];

            $queryBuilder
                ->andWhere(sprintf('YEAR(%s.%s) = :%s_year',$alias, $this->fieldName, $alias))
                ->setParameter($alias.'_year',$values['value']);
        }
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $form     = $event->getForm();
        $options  = $form->getConfig()->getOptions();
        $siteType = ( isset($options['site_type']) && $options['site_type'] == 'advanced') ? new SiteFilterType() : 'site';
        $siteOpt  = ($siteType instanceof SiteFilterType) ? array('include_intense'=>$options['include_intense'],'label'=>'Site'): array();

        $token    = $this->securityContext->getToken();

        if ($this->securityContext->isGranted('ROLE_REGION')) {
            $objectIds = $this->converter->getObjectIdsForRole($token, 'ROLE_REGION');
            if (count($objectIds) > 1) {
                $form->add('region', 'region');
            }
            $form->add('country', 'country');
            $form->add('site', $siteType,$siteOpt);
        } elseif ($this->securityContext->isGranted('ROLE_COUNTRY')) {
            $form->add('site', $siteType,$siteOpt);
        } elseif ($this->securityContext->isGranted('ROLE_SITE')) {
            $objectIds = $this->converter->getObjectIdsForRole($token, 'ROLE_SITE');
            if (count($objectIds) > 1) {
                $form->add('site', $siteType,$siteOpt);
            }
        }

        if ($options['include_filter']) {
            $form->add('filter', 'submit', array(
                'icon' => 'fa fa-search',
                'attr' => array('class' => 'btn btn-sm btn-success','type'=>'submit')));
        }

        if ($options['include_export']) {
            $form->add('export', 'submit', array(
                'icon' => 'fa fa-cloud-download',
                'attr' => array('class' => 'btn btn-sm btn-info','type'=>'submit')));
        }

        if ($options['include_reset']) {
            $form->add('reset', 'submit', array(
                'icon' => 'fa fa-times-circle',
                'attr' => array('class' => 'btn btn-sm btn-danger','type'=>'submit')));
        }
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'include_filter' => true,
                'include_export' => true,
                'include_reset'  => true,
                'include_intense'=> true,
                'year_field'     => 'admDate',
                ));

        $resolver->setDefined(array('site_type'));
        $resolver->setAllowedValues(array('site_type' => array('simple', 'advanced')));
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'BaseQuarterlyFilter';
    }
}
