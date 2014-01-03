<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;

/**
 * Description of DateType
 *
 * @author gnat
 */
class DateType extends AbstractType
{
    public function getName()
    {
        return 'acedate';
    }

    public function getParent()
    {
        return 'date';
    }
}
