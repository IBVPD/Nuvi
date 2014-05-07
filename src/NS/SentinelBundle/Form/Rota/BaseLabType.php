<?php

namespace NS\SentinelBundle\Form\Rota;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BaseLabType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('eiaResult')
            ->add('genotypingDate')
            ->add('genotypingResultg')
            ->add('genotypingResultGSpecify')
            ->add('genotypeResultP')
            ->add('genotypeResultPSpecify')
            ->add('pcrVp6Result')
            ->add('genotypeResultSentToCountry')
            ->add('genotypeResultSentToWHO')
            ->add('isComplete')
            ->add('case')
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rota_base_lab';
    }
}
