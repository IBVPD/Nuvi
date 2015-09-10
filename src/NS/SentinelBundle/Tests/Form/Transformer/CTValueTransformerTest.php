<?php

namespace NS\SentinelBundle\Tests\Form\Transformer;

/**
 * Description of CTValueTransformerTest
 *
 * @author gnat
 */
class CTValueTransformerTest extends \PHPUnit_Framework_TestCase
{

    public function testEmptyTransform()
    {
        $transformer = new \NS\SentinelBundle\Form\Transformer\CTValueTransformer();

        $this->assertEquals(array('choice' => null, 'number' => null), $transformer->transform(null));
        $this->assertEquals(array('choice' => null, 'number' => null), $transformer->transform('null'));

        $this->assertNull($transformer->reverseTransform(array()));
        $this->assertNull($transformer->reverseTransform(array('choice' => null, 'number' => null)));
        $this->assertNull($transformer->reverseTransform(array('choice' => '', 'number' => null)));
    }

    public function testTransformChoice()
    {
        $transformer = new \NS\SentinelBundle\Form\Transformer\CTValueTransformer();

        $this->assertEquals(array('choice' => -1, 'number' => null), $transformer->transform(-1));
        $this->assertEquals(array('choice' => -1, 'number' => null), $transformer->transform(-1.0));
        $this->assertEquals(array('choice' => -1, 'number' => null), $transformer->transform(-1.02));
        $this->assertEquals(array('choice' => -3, 'number' => null), $transformer->transform(-3.02));

        $this->assertEquals(-3, $transformer->reverseTransform(array('choice' => -3.0, 'number' => '')));
        $this->assertEquals(-3, $transformer->reverseTransform(array('choice' => -3.0, 'number' => 30.0)));
        $this->assertEquals(-3, $transformer->reverseTransform(array('choice' => -3.0, 'number' => null)));
    }

    public function testTransformNumber()
    {
        $transformer = new \NS\SentinelBundle\Form\Transformer\CTValueTransformer();

        $this->assertEquals(array('choice' => null, 'number' => 30.02), $transformer->transform(30.02));
        $this->assertEquals(array('choice' => null, 'number' => 0), $transformer->transform(0));
        $this->assertEquals(array('choice' => null, 'number' => 0), $transformer->transform(0.00));

        $this->assertEquals(30.02, $transformer->reverseTransform(array('choice' => '', 'number' => 30.02)));
        $this->assertEquals(30.02, $transformer->reverseTransform(array('choice' => null, 'number' => 30.02)));
    }
}
