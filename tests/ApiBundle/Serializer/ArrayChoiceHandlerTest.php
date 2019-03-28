<?php

namespace NS\ApiBundle\Tests\Serializer;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\SerializationContext;
use NS\ApiBundle\Serializer\ArrayChoiceHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\TranslatorInterface;

class ArrayChoiceHandlerTest extends TestCase
{
    /** @var ArrayChoiceHandler */
    private $handler;

    /** @var TranslatorInterface|MockObject */
    private $translator;

    /** @var JsonSerializationVisitor|MockObject */
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

    public function testInterface(): void
    {
        $this->assertInstanceOf(SubscribingHandlerInterface::class, $this->handler);
    }

    public function testNonExport(): void
    {
        $this->translator->expects($this->never())->method('trans');
        $retValue = $this->handler->serializeToJson($this->visitor, new ArrayChoiceTestType(ArrayChoiceTestType::OPTION_TWO), [], SerializationContext::create()->setGroups(['api']));
        $this->assertEquals(2, $retValue);
    }

    public function testExpanded(): void
    {
        $expected = ['class' => ArrayChoiceTestType::class, 'options' => [1 => 'One-Trans', 2 => 'Two-Trans', 3 => 'Three-Trans']];
        $map = [
            ['One', [], null, null, 'One-Trans'],
            ['Two', [], null, null, 'Two-Trans'],
            ['Three', [], null, null, 'Three-Trans'],
        ];

        $this->translator
            ->method('trans')
            ->willReturnMap($map);

        $retValue = $this->handler->serializeToJson($this->visitor, new ArrayChoiceTestType(), [], SerializationContext::create()->setGroups(['export', 'expanded']));
        $this->assertEquals($expected, $retValue);
    }
}
