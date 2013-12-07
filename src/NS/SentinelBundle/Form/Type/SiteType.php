<?php

namespace NS\SentinelBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\Session;

class SiteType extends AbstractType
{
    private $entityManager;
    private $session;

    public function __construct(ObjectManager $em, Session $session)
    {
        $this->session       = $session;
        $this->entityManager = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new \NS\SentinelBundle\Form\Transformer\IdToReference($this->entityManager,$this->session));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'site_select';
    }

    public function getParent()
    {
        return 'choice';
    }
}
