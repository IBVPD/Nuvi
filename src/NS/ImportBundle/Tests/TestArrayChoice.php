<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 03/09/15
 * Time: 10:58 AM
 */

namespace NS\ImportBundle\Tests;


use NS\UtilBundle\Form\Types\ArrayChoice;

class TestArrayChoice extends ArrayChoice
{
    public function getName()
    {
        return 'TestArrayChoice';
    }
}
