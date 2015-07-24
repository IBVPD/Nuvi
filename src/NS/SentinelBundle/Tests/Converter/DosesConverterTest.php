<?php

namespace NS\SentinelBundle\Tests\Converter;

/**
 * Description of DosesTest
 *
 * @author gnat
 */
class DosesConverterTest extends \PHPUnit_Framework_TestCase
{

    public function testFourDoses()
    {
        $four          = new \NS\SentinelBundle\Form\Types\FourDoses();
        $class         = get_class($four);
        $doseConverter = new \NS\SentinelBundle\Converter\DosesConverter($class);
        $converted     = $doseConverter->__invoke(0);

        $this->assertNull($converted);
    }

    public function testThreeDoses()
    {
        $three = new \NS\SentinelBundle\Form\Types\ThreeDoses();
        $class         = get_class($three);
        $doseConverter = new \NS\SentinelBundle\Converter\DosesConverter($class);
        $converted     = $doseConverter->__invoke(0);

        $this->assertNull($converted);
    }
}