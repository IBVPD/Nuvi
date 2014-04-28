<?php

namespace NS\SentinelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use NS\SentinelBundle\Services\SerializedSites;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use NS\SentinelBundle\Form\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\Types\SerotypeIdentifier;

class BaseLabType extends AbstractType
{
    private $siteSerializer;
    private $em;

    public function __construct(SerializedSites $siteSerializer, ObjectManager $em)
    {
        $this->siteSerializer = $siteSerializer;
        $this->em             = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('labId',                      null,                   array('label'=>'meningitis-rrl-form.lab-id','required'=>true))
            ->add('sampleType',                 'SampleType',           array('label'=>'meningitis-rrl-form.sample-type','required'=>false))
            ->add('dateReceived',               'acedatepicker',        array('label'=>'meningitis-rrl-form.date-received','required'=>false))
            ->add('volume',                     'Volume',               array('label'=>'meningitis-rrl-form.volume','required'=>false))
            ->add('DNAExtractionDate',          'acedatepicker',        array('label'=>'meningitis-rrl-form.dna-extraction-date','required'=>false))
            ->add('DNAVolume',                  null,                   array('label'=>'meningitis-rrl-form.dna-volume','required'=>false))
            ->add('isolateViable',              'TripleChoice',         array('label'=>'meningitis-rrl-form.isolate-viable','required'=>false))
            ->add('isolateType',                'IsolateType',          array('label'=>'meningitis-rrl-form.isolate-type','required'=>false))
            ->add('pathogenIdentifierMethod',   'PathogenIdentifier',   array('label'=>'meningitis-rrl-form.pathogen-id-method','required'=>false, 'attr' => array('data-context-field'=>'pathogenIdentifierMethod')))
            ->add('pathogenIdentifierOther',    null,                   array('label'=>'meningitis-rrl-form.pathogen-id-other', 'required'=>false, 'attr' => array('data-context-field'=>'pathogenIdentifierMethod', 'data-context-value'=>PathogenIdentifier::OTHER)))
            ->add('serotypeIdentifier',         'SerotypeIdentifier',   array('label'=>'meningitis-rrl-form.serotype-id-method','required'=>false, 'attr' => array('data-context-field'=>'serotypeIdentifier')))
            ->add('serotypeIdentifierOther',    null,                   array('label'=>'meningitis-rrl-form.serotype-id-other', 'required'=>false, 'attr' => array('data-context-field'=>'serotypeIdentifier', 'data-context-value'=>SerotypeIdentifier::OTHER)))
            ->add('lytA',                       null,                   array('label'=>'meningitis-rrl-form.lytA','required'=>false))
            ->add('sodC',                       null,                   array('label'=>'meningitis-rrl-form.sodC','required'=>false))
            ->add('hpd',                        null,                   array('label'=>'meningitis-rrl-form.hpd','required'=>false))
            ->add('rNaseP',                     null,                   array('label'=>'meningitis-rrl-form.rNasP','required'=>false))
            ->add('spnSerotype',                null,                   array('label'=>'meningitis-rrl-form.spnSerotype','required'=>false))
            ->add('hiSerotype',                 null,                   array('label'=>'meningitis-rrl-form.hiSerotype','required'=>false))
            ->add('nmSerogroup',                null,                   array('label'=>'meningitis-rrl-form.nmSerogroup','required'=>false))
            ->add('resultSentToCountry',        'acedatepicker',        array('label'=>'meningitis-rrl-form.result-sent-to-country','required'=>false))
            ->add('resultSentToWHO',            'acedatepicker',        array('label'=>'meningitis-rrl-form.result-sent-to-who','required'=>false))
        ;

        $siteSerializer = $this->siteSerializer;
        $em             = $this->em;

        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $e) use($siteSerializer,$em)
                {
                    $data = $e->getData();
                    $form = $e->getForm();

                    if(!$data || ($data && !$data->getCase()))
                    {
                        $form->add('caseId','text',array('required' => true,
                                                         'label'    => 'site-assigned-case-id',
                                                         'mapped'   => false));

                        if($siteSerializer->hasMultipleSites())
                        {
                            $form->add('site','entity',array('required'    => true,
                                                         'mapped'          => false,
                                                         'empty_value'     => 'Please Select...',
                                                         'label'           => 'meningitis-form.site',
                                                         'query_builder'   => $em->getRepository('NS\SentinelBundle\Entity\Site')->getChainQueryBuilder(),
                                                         'class'           => 'NS\SentinelBundle\Entity\Site',
                                                         'auto_initialize' => false));
                        }
                    }
                });

        $builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $e) use($siteSerializer)
                {
                    $data = $e->getData();
                    if($data->hasCase())
                        return;

                    $form   = $e->getForm();
                    $caseId = $form['caseId']->getData();
                    $site   = ($siteSerializer->hasMultipleSites()) ? $form['site']->getData() : $siteSerializer->getSite(true);

                    $case   = new \NS\SentinelBundle\Entity\Meningitis();
                    $case->setCaseId($caseId);
                    $case->setSite($site);

                    if($data instanceof \NS\SentinelBundle\Entity\ReferenceLab)
                        $case->setSentToReferenceLab(true);

                    if($data instanceof \NS\SentinelBundle\Entity\NationalLab)
                        $case->setSentToNationalLab(true);

                    $data->setCase($case);
                    $e->setData($data);
                }
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ibd_base_lab';
    }
}
