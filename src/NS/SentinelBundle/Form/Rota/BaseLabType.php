<?php

namespace NS\SentinelBundle\Form\Rota;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use NS\SentinelBundle\Form\Types\GenotypeResultG;
use NS\SentinelBundle\Form\Types\GenotypeResultP;

class BaseLabType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('labId')
            ->add('eiaResult','EIAResult',              array('required'=>false,'label'=>'external-lab-form.eia-result'))
            ->add('genotypingDate',null,                array('required'=>false,'label'=>'external-lab-form.genotyping-date'))
            ->add('genotypingResultg','GenotypeResultG',array('required'=>false,'label'=>'external-lab-form.genotyping-result-g',         'attr' => array('data-context-child'  => 'genotypingResultG')))
            ->add('genotypingResultGSpecify',null,      array('required'=>false,'label'=>'external-lab-form.genotyping-result-g-specify', 'attr' => array('data-context-parent' => 'genotypingResultG', 'data-context-value' => json_encode(array(GenotypeResultG::OTHER,GenotypeResultG::MIXED)))))
            ->add('genotypeResultP','GenotypeResultP',  array('required'=>false,'label'=>'external-lab-form.genotyping-result-p',         'attr' => array('data-context-child'  => 'genotypingResultP')))
            ->add('genotypeResultPSpecify',null,        array('required'=>false,'label'=>'external-lab-form.genotyping-result-p-specify', 'attr' => array('data-context-parent' => 'genotypingResultP', 'data-context-value' => json_encode(array(GenotypeResultP::OTHER,GenotypeResultP::MIXED)))))
            ->add('pcrVp6Result','EIAResult',           array('required'=>false,'label'=>'external-lab-form.pcr-vp6-result'))
            ->add('genotypeResultSentToCountry','date', array('required'=>false,'label'=>'external-lab-form.genotyping-result-sent-to-country'))
            ->add('genotypeResultSentToWHO','date',     array('required'=>false,'label'=>'external-lab-form.genotyping-result-sent-to-who'))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rotavirus_base_lab';
    }
}
