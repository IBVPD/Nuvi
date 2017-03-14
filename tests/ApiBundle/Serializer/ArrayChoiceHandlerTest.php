<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 14/03/17
 * Time: 1:31 PM
 */

namespace NS\ApiBundle\Tests\Serializer;

use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\SerializationContext;
use NS\ApiBundle\Serializer\ArrayChoiceHandler;
use Symfony\Component\Translation\TranslatorInterface;

class ArrayChoiceHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var ArrayChoiceHandler */
    private $handler;

    /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $translator;

    /** @var JsonSerializationVisitor|\PHPUnit_Framework_MockObject_MockObject */
    private $visitor;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->visitor = $this->createMock(JsonSerializationVisitor::class);
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->handler = new ArrayChoiceHandler($this->translator);
    }

    public function testNonExport()
    {
        $this->translator->expects($this->never())->method('trans');
        $retValue = $this->handler->serializeToJson($this->visitor, new ArrayChoiceTestType(ArrayChoiceTestType::OPTION_TWO), [], SerializationContext::create()->setGroups(['api']));
        $this->assertEquals(2, $retValue);
    }

    public function testExpanded()
    {
        $expected = ['NS\ApiBundle\Tests\Serializer\ArrayChoiceTestType' => ['options' => [1 => 'One-Trans', 2 => 'Two-Trans', 3 => 'Three-Trans']]];
        $map = [
            ['One', [], null, null, 'One-Trans'],
            ['Two', [], null, null, 'Two-Trans'],
            ['Three', [], null, null, 'Three-Trans'],
        ];

        $this->translator
            ->method('trans')
            ->will($this->returnValueMap($map));

        $retValue = $this->handler->serializeToJson($this->visitor, new ArrayChoiceTestType(), [], SerializationContext::create()->setGroups(['export', 'expanded']));
        $this->assertEquals($expected, $retValue);
    }
}
