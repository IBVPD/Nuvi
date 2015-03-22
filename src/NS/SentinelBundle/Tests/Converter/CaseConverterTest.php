<?php

namespace NS\SentinelBundle\Tests\Converter;

use NS\SentinelBundle\Converter\CaseConverter;

/**
 * Description of CaseConverter
 *
 * @author gnat
 */
class CaseConverterTest extends \PHPUnit_Framework_TestCase
{

    public function testCaseConverterName()
    {
        $converter = new CaseConverter($this->getEntityManager(), 'stdClass', 'Standard');
        $this->assertEquals('Standard', $converter->getName());
    }

    public function testConvertById()
    {
        $entityMgr           = $this->getEntityManager();
        $stdClass            = new \stdClass();
        $stdClass->something = 'one';
        $entityMgr->expects($this->once())
            ->method('find')
            ->with('stdClass', 'one')
            ->willReturn($stdClass);

        $converter    = new CaseConverter($entityMgr, 'stdClass', 'Standard');
        $convertedObj = $converter->convert('one');
        $this->assertInstanceOf('\stdClass', $convertedObj);
        $this->assertEquals($stdClass, $convertedObj);
    }

    public function testConvertByArray()
    {
        $params = array('firstName'=>'Bob','caseId'=>'122315');
        $repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with($params)
            ->willReturn(true);
        
        $entityMgr = $this->getEntityManager();
        $entityMgr->expects($this->once())
            ->method('getRepository')
            ->with('stdClass')
            ->willReturn($repository);
        
        $converter = new CaseConverter($entityMgr,'stdClass','NonStandard');
        $this->assertTrue($converter->convert($params));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCaseConvertException()
    {
        $stdObj    = new \stdClass();
        $converter = new CaseConverter($this->getEntityManager(), 'stdClass', 'Standard');
        $converter->convert($stdObj);
    }

    public function getEntityManager()
    {
        return $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
                ->disableOriginalConstructor()
                ->getMock();
    }

}
