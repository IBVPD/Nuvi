<?php

namespace NS\SentinelBundle\Admin;

use NS\SentinelBundle\Form\IBD\Types\IntenseSupport;
use NS\SentinelBundle\Form\Types\SurveillanceConducted;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class SiteAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('code')
            ->add('country')
            ->add('active')
            ->add('rvYearIntro')
            ->add('ibdYearIntro')
            ->add('numberOfBeds')
            ->add('ibdTier')
            ->add('ibdIntenseSupport')
            ->add('ibdLastSiteAssessmentDate')
            ->add('ibdSiteAssessmentScore')
            ->add('rvLastSiteAssessmentDate')
            ->add('ibvpdRl')
            ->add('rvRl')
            ->add('ibdEqaCode')
            ->add('rvEqaCode')
            ->add('surveillanceConducted')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('code')
            ->add('active')
            ->add('country')
            ->add('_action', 'actions', [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                ]
            ])
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('code')
            ->add('country')
            ->add('active', null, ['required'=>false])
            ->add('rvYearIntro')
            ->add('ibdYearIntro')
            ->add('street')
            ->add('city')
            ->add('numberOfBeds')
            ->add('website')
            ->add('ibdTier')
            ->add('ibdIntenseSupport', IntenseSupport::class)
            ->add('ibdLastSiteAssessmentDate')
            ->add('ibdSiteAssessmentScore')
            ->add('rvLastSiteAssessmentDate')
            ->add('ibvpdRl')
            ->add('rvRl')
            ->add('ibdEqaCode')
            ->add('rvEqaCode')
            ->add('surveillanceConducted', SurveillanceConducted::class)
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('code')
            ->add('active')
            ->add('rvYearIntro')
            ->add('ibdYearIntro')
            ->add('street')
            ->add('city')
            ->add('numberOfBeds')
            ->add('website')
            ->add('ibdTier')
            ->add('ibdIntenseSupport')
            ->add('ibdLastSiteAssessmentDate')
            ->add('ibdSiteAssessmentScore')
            ->add('rvLastSiteAssessmentDate')
            ->add('ibvpdRl')
            ->add('rvRl')
            ->add('ibdEqaCode')
            ->add('rvEqaCode')
            ->add('surveillanceConducted')
        ;
    }
}
