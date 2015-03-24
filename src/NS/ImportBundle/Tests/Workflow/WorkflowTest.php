<?php

namespace NS\ImportBundle\Tests\Workflow;

use Ddeboer\DataImport\Reader\ArrayReader;
use Ddeboer\DataImport\Writer\ArrayWriter;
use NS\ImportBundle\Workflow\Workflow;
use PHPUnit_Framework_TestCase;

/**
 * Description of WorkflowTest
 *
 * @author gnat
 */
class WorkflowTest extends PHPUnit_Framework_TestCase
{

    public function testConvertItem()
    {
        $data = array(
            array('first' => array('second' => 'value')),
            array('first' => array('second' => 'value')),
        );

        $converter = $this->getMockBuilder('\Ddeboer\DataImport\ValueConverter\ValueConverterInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $converter->expects($this->any())
            ->method('convert')
            ->with('value')
            ->willReturn(true);

        $output   = array();
        $workflow = new Workflow(new ArrayReader($data));
        $workflow->addWriter(new ArrayWriter($output));
        $workflow->addValueConverter('first.second', $converter);
        $workflow->process();
    }

}
