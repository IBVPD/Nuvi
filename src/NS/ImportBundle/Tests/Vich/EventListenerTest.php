<?php

namespace NS\ImportBundle\Tests\Vich;

use NS\ImportBundle\Entity\Import;
use NS\ImportBundle\Vich\EventListener;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Mapping\PropertyMapping;

class EventListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testNonImport()
    {
        $listener = $this->getMockBuilder('NS\ImportBundle\Vich\EventListener')
            ->setMethods(array('onPostUpload', 'convertToUtf8'))
            ->getMock();

        $listener->expects($this->never())->method('convertToUtf8');

        $stdObj = new \stdClass();

        $listener->onPostUpload(new Event($stdObj, new PropertyMapping('sourceFile', 'sourceFile')));
    }

    public function testNonSourceProperty()
    {
        $file = new File(__DIR__ . '/../Fixtures/EMR-IBD-headers.csv');
        $listener = $this->getMockBuilder('NS\ImportBundle\Vich\EventListener')
            ->setMethods(array('onPostUpload', 'convertToUtf8'))
            ->getMock();

        $listener->expects($this->never())->method('convertToUtf8');

        $mockUser = $this->createMock('Symfony\Component\Security\Core\User\UserInterface');
        $import = new Import($mockUser);
        $import->setSourceFile($file);

        $listener->onPostUpload(new Event($import, new PropertyMapping('messages', 'messages')));
    }

    public function testNonCsvFile()
    {
        $file = new File(__DIR__ . '/../Fixtures/EMR-IBD-headers.xls');
        $listener = $this->getMockBuilder('NS\ImportBundle\Vich\EventListener')
            ->setMethods(array('onPostUpload', 'convertToUtf8'))
            ->getMock();

        $listener->expects($this->never())->method('convertToUtf8');


        $mockUser = $this->createMock('Symfony\Component\Security\Core\User\UserInterface');
        $import = new Import($mockUser);
        $import->setSourceFile($file);

        $listener->onPostUpload(new Event($import, new PropertyMapping('source', 'source')));
    }

    /**
     * @param $filename
     * @expectedException \NS\ImportBundle\Vich\NonUTF8FileException
     * @dataProvider getCsvFiles
     */
    public function testCsvFile($filename)
    {
        $filePath = realpath(__DIR__ . $filename);
        $newFile = '/tmp/headers.csv';
        if (!copy($filePath, $newFile)) {
            $this->fail(sprintf('Unable to copy(%s,%s)', $filePath, $newFile));
        }

        $orgEncoding = mb_detect_encoding(file_get_contents($newFile));
        $this->assertTrue($orgEncoding != 'UTF-8');

        $file = new File($newFile);

        $listener = new EventListener();

        $mockUser = $this->createMock('Symfony\Component\Security\Core\User\UserInterface');
        $import = new Import($mockUser);
        $import->setSourceFile($file);

        $listener->onPostUpload(new Event($import, new PropertyMapping('sourceFile', 'sourceFile')));
    }

    public function getCsvFiles()
    {
        return [
            ['/../Fixtures/EMR-IBD-headers-utf16.csv'],
            ['/../Fixtures/WHO-Binary.csv']
        ];
    }
}
