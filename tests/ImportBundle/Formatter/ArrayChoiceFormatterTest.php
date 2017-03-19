<?php

/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 2017-03-18
 * Time: 9:01 PM
 */

namespace NS\ImportBundle\Tests\Formatter;

use NS\ImportBundle\Formatter\ArrayChoiceFormatter;
use NS\SentinelBundle\Form\IBD\Types\Diagnosis;
use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\Translation\TranslatorInterface;

class ArrayChoiceFormatterTest extends \PHPUnit_Framework_TestCase
{
    /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $translator;

    /** @var ArrayChoiceFormatter */
    private $formatter;

    public function setUp()
    {
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->formatter = new ArrayChoiceFormatter($this->translator);
    }

    public function testDefaults()
    {
        $choice = new Diagnosis();
        $this->assertTrue($this->formatter->supports($choice));
        $this->assertTrue($this->formatter->supports($this->createMock(ArrayChoice::class)));
    }

    public function testNonSelection()
    {
        $choice = new Diagnosis();
        $this->assertNull($this->formatter->format($choice, new PropertyPath('nothing')));
    }

    public function testRegularFormat()
    {
        $expected = Diagnosis::MULTIPLE;
        $choice = new Diagnosis($expected);

        $this->assertEquals($expected,$this->formatter->format($choice, new PropertyPath('nothing')));
    }

    public function testPahoFormat()
    {
        $this->translator->expects($this->once())->method('trans')->with('Suspected meningitis')->willReturn('Suspected meningitis Translated');
        $this->formatter->usePahoFormat();
        $choice = new Diagnosis(Diagnosis::SUSPECTED_MENINGITIS);

        $this->assertEquals('1 => Suspected meningitis Translated',$this->formatter->format($choice, new PropertyPath('nothing')));

    }
}
