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
        $type = new SampleType();
        $values = $type->getValues();
        
        $builder
            ->add('labId',                      null,                   array('label'=>'meningitis-rrl-form.lab-id','required'=>true))
            ->add('dateReceived',               'acedatepicker',        array('label'=>'meningitis-rrl-form.date-received','required'=>false))
            ->add('sampleType',                 'SampleType',           array('label'=>'meningitis-rrl-form.sample-type','required'=>false,'expanded'=>true,
                                                                              'attr' => array('data-context-child'=>'sampleType')
                                                                             ))
            ->add('volume',                     'Volume',               array('label'=>'meningitis-rrl-form.volume','required'=>false,
                                                                              'attr' => array('data-context-parent'=>'sampleType','data-context-value'=>  json_encode(array($values[SampleType::CSF],$values[SampleType::WHOLE],$values[SampleType::PLEURAL])) )
                                                                             ))
            ->add('DNAExtractionDate',          'acedatepicker',        array('label'=>'meningitis-rrl-form.dna-extraction-date','required'=>false,
                                                                              'attr' => array('data-context-parent'=>'sampleType','data-context-value'=>  json_encode(array($values[SampleType::CSF],$values[SampleType::WHOLE],$values[SampleType::PLEURAL],$values[SampleType::ISOLATE])))
                                                                             ))
            ->add('DNAVolume',                  null,                   array('label'=>'meningitis-rrl-form.dna-volume','required'=>false,
                                                                              'attr' => array('data-context-parent'=>'sampleType','data-context-value'=>  json_encode(array($values[SampleType::CSF],$values[SampleType::WHOLE],$values[SampleType::PLEURAL],$values[SampleType::ISOLATE])))
                                                                             ))
            ->add('isolateViable',              'TripleChoice',         array('label'=>'meningitis-rrl-form.isolate-viable','required'=>false,
                                                                              'attr' => array('data-context-parent'=>'sampleType','data-context-value'=> $values[SampleType::ISOLATE])
                                                                             ))
            ->add('isolateType',                'IsolateType',          array('label'=>'meningitis-rrl-form.isolate-type','required'=>false,
                                                                              'attr' => array('data-context-parent'=>'sampleType','data-context-value'=> $values[SampleType::ISOLATE])
                                                                             ))
            ->add('samples',                    'collection',           array('type' => new LabSampleType(),'allow_add' => true,'by_reference'=>false))
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
