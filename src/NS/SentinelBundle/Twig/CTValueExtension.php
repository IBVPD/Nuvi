<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 2016-04-05
 * Time: 10:17 AM
 */

namespace NS\SentinelBundle\Twig;


use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

class CTValueExtension extends \Twig_Extension implements TranslationContainerInterface
{
    /**
     * @var array
     */
    private $choices = [
        "-3.0" => 'No CT Value',
        "-2.0" => 'Negative',
        "-1.0" => 'Undetermined',
    ];

    /**
     * @inheritDoc
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('ct_value', [$this, 'renderCTValue'])
        ];
    }

    /**
     * @param $value
     * @return mixed
     */
    public function renderCTValue($value)
    {
        if (isset($this->choices[$value])) {
            return $this->choices[$value];
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'CTValueExtension';
    }

    /**
     * @inheritDoc
     */
    public static function getTranslationMessages()
    {
        return [
            new Message('No CT Value'),
            new Message('Negative'),
            new Message('Undetermined'),
        ];
    }
}
