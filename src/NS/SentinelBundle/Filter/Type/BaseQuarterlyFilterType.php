<?php

namespace NS\SentinelBundle\Filter\Type;

use Doctrine\ORM\QueryBuilder;
use DoctrineExtensions\Query\Mysql\Year;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\NumberFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use NS\SecurityBundle\Role\ACLConverter;
use NS\SentinelBundle\Entity\ZeroReport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BaseQuarterlyFilterType extends AbstractType
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var AuthorizationCheckerInterface */
    private $authChecker;

    /** @var ACLConverter */
    private $converter;

    /** @var string */
    protected $fieldName = 'adm_date';

    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authChecker, ACLConverter $converter)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authChecker  = $authChecker;
        $this->converter    = $converter;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('year', NumberFilterType::class, ['label' => 'report-filter-form.year', 'apply_filter'=> [$this, 'filterYear']]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'preSetData']);
    }

    public function filterYear(QueryInterface $filterQuery, $field, $values): void
    {
        if ($values['value'] > 0) {
            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = $filterQuery->getQueryBuilder();

            $alias = $values['alias'];
            $roots = $queryBuilder->getRootEntities();

            if (in_array(ZeroReport::class, $roots, true)) {
                $queryBuilder
                    ->andWhere(sprintf('%s.yearMonth BETWEEN :%sYearStart AND :%sYearEnd', $alias, $alias, $alias))
                    ->setParameter($alias . 'YearStart', "{$values['value']}00")
                    ->setParameter($alias . 'YearEnd', "{$values['value']}12");
            } else {
                $config = $queryBuilder->getEntityManager()->getConfiguration();
                $config->addCustomDatetimeFunction('YEAR', Year::class);
                $queryBuilder
                    ->andWhere(sprintf('YEAR(%s.%s) = :%s_year', key($queryBuilder->getDQLPart('join')), $this->fieldName, $alias))
                    ->setParameter($alias . '_year', $values['value']);
            }
        }
    }

    public function preSetData(FormEvent $event): void
    {
        $form     = $event->getForm();
        $options  = $form->getConfig()->getOptions();
        $siteType = (isset($options['site_type']) && $options['site_type'] === 'advanced') ? SiteFilterType::class : SiteType::class;
        $siteOpt  = SiteFilterType::class === $siteType ? ['required' => false, 'include_intense' => $options['include_intense'], 'label' => 'Site'] : [];

        $token    = $this->tokenStorage->getToken();

        if ($this->authChecker->isGranted('ROLE_REGION')) {
            $objectIds = $this->converter->getObjectIdsForRole($token, 'ROLE_REGION');
            if (count($objectIds) > 1) {
                $form->add('region', RegionType::class);
            }
            $form->add('country', CountryType::class, ['required'=>false, 'placeholder'=>'']);
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
            $form->add('filter', SubmitType::class, [
                'label'=> 'filter',
                'icon' => 'fa fa-search',
                'attr' => ['class' => 'btn btn-sm btn-success', 'type'=>'submit']]);
        }

        if ($options['include_export']) {
            $form->add('export', SubmitType::class, [
                'label'=>'export',
                'icon' => 'fa fa-cloud-download',
                'attr' => ['class' => 'btn btn-sm btn-info', 'type'=>'submit']]);
        }

        if ($options['include_reset']) {
            $form->add('reset', SubmitType::class, [
                'label'=>'reset',
                'icon' => 'fa fa-times-circle',
                'attr' => ['class' => 'btn btn-sm btn-danger', 'type'=>'submit']]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
                'include_filter' => true,
                'include_export' => true,
                'include_reset'  => true,
                'include_intense'=> true,
                'year_field'     => 'adm_date',
        ]);

        $resolver->setDefined(['site_type']);
        $resolver->setAllowedValues('site_type', ['simple', 'advanced']);
    }
}
