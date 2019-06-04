<?php

namespace NS\SentinelBundle\Filter\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteType extends BaseObject
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'choice_label' => 'ajaxDisplay',
            'group_by' => static function ($val, $key, $index) {
                return (string)$val->getcountry();
            },
        ]);
    }
}
