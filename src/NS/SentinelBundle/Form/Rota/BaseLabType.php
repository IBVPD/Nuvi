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
            ->add('labId')
            ->add('eiaResult','EIAResult')
            ->add('genotypingDate')
            ->add('genotypingResultg','GenotypeResultG')
            ->add('genotypingResultGSpecify','GenotypeResultGSpecify')
            ->add('genotypeResultP','GenotypeResultP')
            ->add('genotypeResultPSpecify','GenotypeResultPSpecify')
            ->add('pcrVp6Result','EIAResult')
            ->add('genotypeResultSentToCountry')
            ->add('genotypeResultSentToWHO')
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
