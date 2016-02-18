<?php

namespace NS\SentinelBundle\Form\Rota;

use NS\SentinelBundle\Form\Types\GenotypeResultG;
use NS\SentinelBundle\Form\Types\GenotypeResultP;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class BaseLabType
 * @package NS\SentinelBundle\Form\Rota
 */
class BaseLabType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('labId')
            ->add('dateReceived','acedatepicker',       array('required'=>false,'label'=>'external-lab-form.date-received'))
            ->add('genotypingDate','acedatepicker',     array('required'=>false,'label'=>'external-lab-form.genotyping-date'))
            ->add('genotypingResultG','GenotypeResultG',array('required'=>false,'label'=>'external-lab-form.genotyping-result-g',         'attr' => array('data-context-child'  => 'genotypingResultG')))
            ->add('genotypingResultGSpecify',null,      array('required'=>false,'label'=>'external-lab-form.genotyping-result-g-specify', 'attr' => array('data-context-parent' => 'genotypingResultG', 'data-context-value' => json_encode(array(GenotypeResultG::OTHER,GenotypeResultG::MIXED)))))
            ->add('genotypeResultP','GenotypeResultP',  array('required'=>false,'label'=>'external-lab-form.genotyping-result-p',         'attr' => array('data-context-child'  => 'genotypingResultP')))
            ->add('genotypeResultPSpecify',null,        array('required'=>false,'label'=>'external-lab-form.genotyping-result-p-specify', 'attr' => array('data-context-parent' => 'genotypingResultP', 'data-context-value' => json_encode(array(GenotypeResultP::OTHER,GenotypeResultP::MIXED)))))
            ->add('pcrVp6Result', 'ElisaResult', array('required' => false, 'label' => 'external-lab-form.pcr-vp6-result'))
            ->add('comment',null,array('required'=>false,'label'=>'external-lab-form.comment'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'rotavirus_base_lab';
    }
}
