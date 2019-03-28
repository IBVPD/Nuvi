<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 14/03/17
 * Time: 1:47 PM
 */

namespace NS\ApiBundle\Tests\Serializer;

use NS\UtilBundle\Form\Types\ArrayChoice;

class ArrayChoiceTestType extends ArrayChoice
{
    const OPTION_ONE = 1;
    const OPTION_TWO = 2;
    const OPTION_THREE = 3;

    protected $values = [
        self::OPTION_ONE => 'One',
        self::OPTION_TWO => 'Two',
        self::OPTION_THREE => 'Three',
    ];
}
