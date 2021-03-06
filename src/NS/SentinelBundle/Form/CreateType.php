<?php

namespace NS\SentinelBundle\Form;

use Doctrine\Common\Persistence\ObjectManager;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Form\Types\CaseCreationType;
use NS\SentinelBundle\Interfaces\SerializedSitesInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateType extends AbstractType
{
    /** @var SerializedSitesInterface */
    private $siteSerializer;

    /** @var ObjectManager */
    private $entityMgr;

    public function __construct(SerializedSitesInterface $siteSerializer, ObjectManager $entityMgr)
    {
        $this->siteSerializer = $siteSerializer;
        $this->entityMgr      = $entityMgr;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('caseId', null, ['label' => 'site-assigned-case-id', 'constraints' => [new NotBlank()]])
            ->add('type', CaseCreationType::class, ['description' => 'This should always be "1"', 'label' => 'What type of case data?'])
        ;

        if ($this->siteSerializer->hasMultipleSites()) {
            $queryBuilder = $this->entityMgr->getRepository('NS\SentinelBundle\Entity\Site')->getChainQueryBuilder()->orderBy('c.name,s.name', 'ASC');
            $builder->add('site', EntityType::class, [
                'choice_label'    => 'ajaxDisplay',
                'group_by'        => function($val, $key, $index) {
                    /** @var Site $val */
                    return (string)$val->getCountry();
                },
                'required'        => true,
                'mapped'          => false,
                'placeholder'     => 'Please Select...',
                'label'           => 'ibd-form.site',
                'query_builder'   => $queryBuilder,
                'class'           => Site::class,
                'constraints'     => [new NotBlank()],
                'auto_initialize' => false]);
        }
    }
}
