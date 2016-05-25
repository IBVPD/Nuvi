<?php

namespace NS\SentinelBundle\Filter\Type;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\NumberFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use \NS\SecurityBundle\Role\ACLConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class BaseQuarterlyFilterType extends AbstractType
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * @var ACLConverter
     */
    private $converter;

    /**
     * @var string
     */
    protected $fieldName = 'adm_date';

    /**
     * BaseQuarterlyFilterType constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param AuthorizationCheckerInterface $authChecker
     * @param ACLConverter $converter
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authChecker, ACLConverter $converter)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authChecker  = $authChecker;
        $this->converter    = $converter;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('year', NumberFilterType::class, array('label' => 'report-filter-form.year', 'apply_filter'=>array($this, 'filterYear')));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'preSetData'));
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

            $alias = $values['alias'];
            $roots = $queryBuilder->getRootEntities();

            if (in_array('NS\SentinelBundle\Entity\ZeroReport', $roots)) {
                $queryBuilder
                    ->andWhere(sprintf('%s.yearMonth BETWEEN :%sYearStart AND :%sYearEnd', $alias, $alias, $alias))
                    ->setParameter($alias . 'YearStart', "{$values['value']}00")
                    ->setParameter($alias . 'YearEnd', "{$values['value']}12");
            } else {
                $config = $queryBuilder->getEntityManager()->getConfiguration();
                $config->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
                $queryBuilder
                    ->andWhere(sprintf('YEAR(%s.%s) = :%s_year', $alias, $this->fieldName, $alias))
                    ->setParameter($alias . '_year', $values['value']);
            }
        }
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $form     = $event->getForm();
        $options  = $form->getConfig()->getOptions();
        $siteType = (isset($options['site_type']) && $options['site_type'] == 'advanced') ? new SiteFilterType() : 'site';
        $siteOpt  = ($siteType instanceof SiteFilterType) ? array('include_intense'=>$options['include_intense'],'label'=>'Site'): array();

        $token    = $this->tokenStorage->getToken();

        if ($this->authChecker->isGranted('ROLE_REGION')) {
            $objectIds = $this->converter->getObjectIdsForRole($token, 'ROLE_REGION');
            if (count($objectIds) > 1) {
                $form->add('region', 'NS\SentinelBundle\Filter\Type\RegionType');
            }
            $form->add('country', 'NS\SentinelBundle\Filter\Type\CountryType', array('required'=>false, 'placeholder'=>''));
            $form->add('site', $siteType, $siteOpt);
        } elseif ($this->authChecker->isGranted('ROLE_COUNTRY')) {
            $form->add('site', $siteType, $siteOpt);
        } elseif ($this->authChecker->isGranted('ROLE_SITE')) {
            $objectIds = $this->converter->getObjectIdsForRole($token, 'ROLE_SITE');
            if (count($objectIds) > 1) {
                $form->add('site', $siteType, $siteOpt);
            }
        }

        if ($options['include_filter']) {
            $form->add('filter', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array(
                'label'=> 'filter',
                'icon' => 'fa fa-search',
                'attr' => array('class' => 'btn btn-sm btn-success', 'type'=>'submit')));
        }

        if ($options['include_export']) {
            $form->add('export', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array(
                'label'=>'export',
                'icon' => 'fa fa-cloud-download',
                'attr' => array('class' => 'btn btn-sm btn-info', 'type'=>'submit')));
        }

        if ($options['include_reset']) {
            $form->add('reset', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', array(
                'label'=>'reset',
                'icon' => 'fa fa-times-circle',
                'attr' => array('class' => 'btn btn-sm btn-danger', 'type'=>'submit')));
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
                'year_field'     => 'adm_date',
                ));

        $resolver->setDefined(array('site_type'));
        $resolver->setAllowedValues('site_type', array('simple', 'advanced'));
    }
}
