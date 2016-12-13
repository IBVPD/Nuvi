<?php

namespace NS\SentinelBundle\Tests\Form\Transformer;
use NS\SentinelBundle\Form\IBD\Transformer\CTValueTransformer;

/**
 * Description of CTValueTransformerTest
 *
 * @author gnat
 */
class CTValueTransformerTest extends \PHPUnit_Framework_TestCase
{

    public function testEmptyTransform()
    {
        $transformer = new CTValueTransformer();

        $this->assertEquals(['choice' => null, 'number' => null], $transformer->transform(null));
        $this->assertEquals(['choice' => null, 'number' => null], $transformer->transform('null'));

        $this->assertNull($transformer->reverseTransform([]));
        $this->assertNull($transformer->reverseTransform(['choice' => null, 'number' => null]));
        $this->assertNull($transformer->reverseTransform(['choice' => '', 'number' => null]));
    }

    public function testTransformChoice()
    {
        $transformer = new CTValueTransformer();

        $this->assertEquals(['choice' => -1, 'number' => null], $transformer->transform(-1));
        $this->assertEquals(['choice' => -1, 'number' => null], $transformer->transform(-1.0));
        $this->assertEquals(['choice' => -1, 'number' => null], $transformer->transform(-1.02));
        $this->assertEquals(['choice' => -3, 'number' => null], $transformer->transform(-3.02));

        $this->assertEquals(-3, $transformer->reverseTransform(['choice' => -3.0, 'number' => '']));
        $this->assertEquals(-3, $transformer->reverseTransform(['choice' => -3.0, 'number' => 30.0]));
        $this->assertEquals(-3, $transformer->reverseTransform(['choice' => -3.0, 'number' => null]));
    }

    public function testTransformNumber()
    {
        $transformer = new CTValueTransformer();

        $this->assertEquals(['choice' => null, 'number' => 30.02], $transformer->transform(30.02));
        $this->assertEquals(['choice' => null, 'number' => 0], $transformer->transform(0));
        $this->assertEquals(['choice' => null, 'number' => 0], $transformer->transform(0.00));

        $this->assertEquals(30.02, $transformer->reverseTransform(['choice' => '', 'number' => 30.02]));
        $this->assertEquals(30.02, $transformer->reverseTransform(['choice' => null, 'number' => 30.02]));
    }
}
