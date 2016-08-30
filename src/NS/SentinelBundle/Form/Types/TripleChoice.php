<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class TripleChoice extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const NO      = 0;
    const YES     = 1;
    const UNKNOWN = 99;

    protected $values = array(
                            self::NO      => 'No',
                            self::YES     => 'Yes',
                            self::UNKNOWN => 'Unknown');

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $values = $this->values;

        $resolver->setDefaults(array(
            'empty_value' => 'Please Select...',
            'exclude_unknown' => false,
            'choices' => function (Options $options) use ($values) {
                if ($options['exclude_unknown']) {
                    unset($values[self::UNKNOWN]);
                }

                return $values;
            }
        ));

        $resolver->setDefined(array('special_values'));
        $resolver->addAllowedTypes(array('special_values' => 'array'));
    }
}
