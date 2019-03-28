<?php

namespace NS\SentinelBundle\Tests\Converter;

use NS\SentinelBundle\Converter\DosesConverter;
use NS\SentinelBundle\Form\Types\FourDoses;
use NS\SentinelBundle\Form\Types\ThreeDoses;
use PHPUnit\Framework\TestCase;

/**
 * Description of DosesTest
 *
 * @author gnat
 */
class DosesConverterTest extends TestCase
{
    public function testFourDoses(): void
    {
        $four          = new FourDoses();
        $class         = get_class($four);
        $doseConverter = new DosesConverter($class,'Both');
        $converted     = $doseConverter->__invoke(0);

        $this->assertNull($converted);
    }

    public function testThreeDoses(): void
    {
        $three = new ThreeDoses();
        $class         = get_class($three);
        $doseConverter = new DosesConverter($class,'Both');
        $converted     = $doseConverter->__invoke(0);

        $this->assertNull($converted);
    }
}
