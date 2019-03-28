<?php

namespace NS\ImportBundle\Tests\Vich;

use NS\ImportBundle\Entity\Import;
use NS\ImportBundle\Vich\EventListener;
use NS\ImportBundle\Vich\NonUTF8FileException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Symfony\Component\Security\Core\User\UserInterface;

class EventListenerTest extends TestCase
{
    public function testNonImport(): void
    {
        $listener = $this->getMockBuilder(EventListener::class)
            ->setMethods(['onPostUpload', 'convertToUtf8'])
            ->getMock();

        $listener->expects($this->never())->method('convertToUtf8');

        $stdObj = new stdClass();

        $listener->onPostUpload(new Event($stdObj, new PropertyMapping('sourceFile', 'sourceFile')));
    }

    public function testNonSourceProperty(): void
    {
        $file = new File(__DIR__ . '/../Fixtures/EMR-IBD-headers.csv');
        $listener = $this->getMockBuilder(EventListener::class)
            ->setMethods(['onPostUpload', 'convertToUtf8'])
            ->getMock();

        $listener->expects($this->never())->method('convertToUtf8');

        $mockUser = $this->createMock(UserInterface::class);
        $import = new Import($mockUser);
        $import->setSourceFile($file);

        $listener->onPostUpload(new Event($import, new PropertyMapping('messages', 'messages')));
    }

    public function testNonCsvFile(): void
    {
        $file = new File(__DIR__ . '/../Fixtures/EMR-IBD-headers.xls');
        $listener = $this->getMockBuilder(EventListener::class)
            ->setMethods(['onPostUpload', 'convertToUtf8'])
            ->getMock();

        $listener->expects($this->never())->method('convertToUtf8');


        $mockUser = $this->createMock(UserInterface::class);
        $import = new Import($mockUser);
        $import->setSourceFile($file);

        $listener->onPostUpload(new Event($import, new PropertyMapping('source', 'source')));
    }

    /**
     * @param $filename
     * @dataProvider getCsvFiles
     */
    public function testCsvFile($filename): void
    {
        $this->expectException(NonUTF8FileException::class);
        $filePath = realpath(__DIR__ . $filename);
        $newFile = '/tmp/headers.csv';
        if (!copy($filePath, $newFile)) {
            $this->fail(sprintf('Unable to copy(%s,%s)', $filePath, $newFile));
        }

        $orgEncoding = mb_detect_encoding(file_get_contents($newFile));
        $this->assertNotSame($orgEncoding, 'UTF-8');

        $file = new File($newFile);

        $listener = new EventListener();

        $mockUser = $this->createMock(UserInterface::class);
        $import = new Import($mockUser);
        $import->setSourceFile($file);

        $listener->onPostUpload(new Event($import, new PropertyMapping('sourceFile', 'sourceFile')));
    }

    public function getCsvFiles(): array
    {
        return [
            ['/../Fixtures/EMR-IBD-headers-utf16.csv'],
            ['/../Fixtures/WHO-Binary.csv']
        ];
    }
}
