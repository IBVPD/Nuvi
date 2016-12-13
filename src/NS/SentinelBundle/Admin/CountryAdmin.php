<?php

namespace NS\SentinelBundle\Admin;

use Lunetics\LocaleBundle\Form\Extension\Type\LocaleType;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use NS\SentinelBundle\Form\Types\TripleChoice;

class CountryAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $type = new TripleChoice();

        $datagridMapper
            ->add('code')
            ->add('name')
            ->add('region')
            ->add('active')
            ->add('tracksPneumonia')
            ->add('hasReferenceLab')
            ->add('hasNationalLab')
            ->add('gaviEligible', 'doctrine_orm_callback', ['callback' => [$this, 'filterGaviEligible'], 'field_type' => 'choice', 'field_options' => ['choices' => $type->getValues(), 'multiple' => true]])
            ->add('hibVaccineIntro')
            ->add('pcvVaccineIntro')
            ->add('rvVaccineIntro');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('code')
            ->add('name')
            ->add('gaviEligible')
            ->add('tracksPneumonia')
            ->add('hasReferenceLab')
            ->add('hasNationalLab')
            ->add('active')
            ->add('_action', 'actions', [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                ]
            ]);
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('code')
            ->add('name')
            ->add('language', LocaleType::class, ['required' => false])
            ->add('tracksPneumonia', null, ['required' => false])
            ->add('hasReferenceLab', null, ['required' => false])
            ->add('hasNationalLab', null, ['required' => false])
            ->add('gaviEligible', TripleChoice::class, ['required' => false])
            ->add('hibVaccineIntro')
            ->add('pcvVaccineIntro')
            ->add('rvVaccineIntro')
            ->add('active', null, ['required' => false])
            ->add('region');
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('code')
            ->add('name')
            ->add('tracksPneumonia')
            ->add('hasReferenceLab')
            ->add('hasNationalLab')
            ->add('gaviEligible')
            ->add('hibVaccineIntro')
            ->add('pcvVaccineIntro')
            ->add('rvVaccineIntro')
            ->add('region')
            ->add('sites');
    }

    /**
     * @param $queryBuilder
     * @param string $alias
     * @param string $field
     * @param array $value
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function filterGaviEligible($queryBuilder, $alias, $field, $value)
    {
        if (empty($value['value'])) {
            return;
        }

        $out = $params = [];
        foreach ($value['value'] as $x => $role) {
            $out[] = "$alias.gaviEligible = :type$x";
            $params["type$x"] = $role;
        }

        $queryBuilder->andWhere("(" . implode(" OR ", $out) . ")")->setParameters($params);
    }
}
