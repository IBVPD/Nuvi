<?php

namespace NS\SentinelBundle\Tests\Converter;

use NS\SentinelBundle\Converter\DosesConverter;
use NS\SentinelBundle\Form\Types\FourDoses;
use NS\SentinelBundle\Form\Types\ThreeDoses;

/**
 * Description of DosesTest
 *
 * @author gnat
 */
class DosesConverterTest extends \PHPUnit_Framework_TestCase
{

    public function testFourDoses()
    {
        $four          = new FourDoses();
        $class         = get_class($four);
        $doseConverter = new DosesConverter($class,'Both');
        $converted     = $doseConverter->__invoke(0);

        $this->assertNull($converted);
    }

    public function testThreeDoses()
    {
        $three = new ThreeDoses();
        $class         = get_class($three);
        $doseConverter = new DosesConverter($class,'Both');
        $converted     = $doseConverter->__invoke(0);

        $this->assertNull($converted);
    }
}
