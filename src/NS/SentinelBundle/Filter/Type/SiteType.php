<?php

namespace NS\SentinelBundle\Filter\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of Site
 *
 * @author gnat
 */
class SiteType extends BaseObject
{
    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'choice_label'=>'ajaxDisplay',
            'group_by' => function($val,$key,$index) {
                return (string)$val->getcountry();
            }
        ]);
    }

}
