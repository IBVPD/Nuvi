<?php

namespace NS\SentinelBundle\Form\IBD;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use NS\SentinelBundle\Form\Types\SampleType;

class BaseLabType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('labId',                      null,                   array('label'=>'meningitis-rrl-form.lab-id','required'=>true))
            ->add('dateReceived',               'acedatepicker',        array('label'=>'meningitis-rrl-form.date-received','required'=>false))
            ->add('sampleType',                 'SampleType',           array('label'=>'meningitis-rrl-form.sample-type','required'=>false,'expanded'=>true,
                                                                              'attr' => array('data-context-child'=>'sampleType')
                                                                             ))
            ->add('volume',                     'Volume',               array('label'=>'meningitis-rrl-form.volume','required'=>false,
                                                                              'attr' => array('data-context-parent'=>'sampleType','data-context-value'=>  json_encode(array(SampleType::WHOLE,SampleType::PLEURAL)) )
                                                                             ))
            ->add('DNAExtractionDate',          'acedatepicker',        array('label'=>'meningitis-rrl-form.dna-extraction-date','required'=>false,
                                                                              'attr' => array('data-context-parent'=>'sampleType','data-context-value'=>  json_encode(array(SampleType::ISOLATE,SampleType::WHOLE,SampleType::PLEURAL)))
                                                                             ))
            ->add('DNAVolume',                  null,                   array('label'=>'meningitis-rrl-form.dna-volume','required'=>false,
                                                                              'attr' => array('data-context-parent'=>'sampleType','data-context-value'=>  json_encode(array(SampleType::ISOLATE,SampleType::WHOLE,SampleType::PLEURAL)))
                                                                             ))
            ->add('isolateViable',              'TripleChoice',         array('label'=>'meningitis-rrl-form.isolate-viable','required'=>false,
                                                                              'attr' => array('data-context-parent'=>'sampleType','data-context-value'=> SampleType::ISOLATE)
                                                                             ))
            ->add('isolateType',                'IsolateType',          array('label'=>'meningitis-rrl-form.isolate-type','required'=>false,
                                                                              'attr' => array('data-context-parent'=>'sampleType','data-context-value'=>  json_encode(array(SampleType::ISOLATE,SampleType::WHOLE,SampleType::PLEURAL)))
                                                                             ))
            ->add('samples')
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ibd_base_lab';
    }
}
