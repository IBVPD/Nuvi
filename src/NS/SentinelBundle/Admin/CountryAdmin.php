<?php

namespace NS\SentinelBundle\Admin;

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
            ->add('name')
            ->add('code')
            ->add('region')
            ->add('active')
            ->add('tracksPneumonia')
            ->add('hasReferenceLab')
            ->add('hasNationalLab')
            ->add('gaviEligible', 'doctrine_orm_callback', array('callback' => array($this, 'filterGaviEligible'), 'field_type' => 'choice', 'field_options' => array('choices' => $type->getValues(), 'multiple' => true)))
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
            ->add('code')
            ->add('name')
            ->add('gaviEligible')
            ->add('tracksPneumonia')
            ->add('hasReferenceLab')
            ->add('hasNationalLab')
            ->add('active')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ));
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('code')
            ->add('name')
            ->add('language', 'lunetics_locale', array('required' => false))
            ->add('tracksPneumonia', null, array('required' => false))
            ->add('hasReferenceLab', null, array('required' => false))
            ->add('hasNationalLab', null, array('required' => false))
            ->add('gaviEligible', 'TripleChoice', array('required' => false))
            ->add('hibVaccineIntro')
            ->add('pcvVaccineIntro')
            ->add('rvVaccineIntro')
            ->add('active', null, array('required' => false))
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

        $out = $params = array();
        foreach ($value['value'] as $x => $role) {
            $out[] = "$alias.gaviEligible = :type$x";
            $params["type$x"] = $role;
        }

        $queryBuilder->andWhere("(" . implode(" OR ", $out) . ")")->setParameters($params);
    }
}
