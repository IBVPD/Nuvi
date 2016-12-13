<?php

namespace NS\ImportBundle\Vich;

use NS\ImportBundle\Entity\Import;
use NS\ImportBundle\Reader\ReaderFactory;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Event\Event;

/**
 * Class EventListener
 * @package NS\ImportBundle\Vich
 */
class EventListener
{
    private $readerFactory;

    /**
     * EventListener constructor.
     */
    public function __construct()
    {
        $this->readerFactory = new ReaderFactory();
    }

    /**
     * @param Event $event
     */
    public function onPostUpload(Event $event)
    {
        $obj      = $event->getObject();
        $property = $event->getMapping()->getFilePropertyName();

        if ($obj instanceof Import && $property == 'sourceFile' && $this->readerFactory->getExtension($obj->getSourceFile()) == 'csv') {
            $this->checkUtf8($obj->getSourceFile());
        }
    }

    /**
     * @param File $file
     */
    private function checkUtf8(File $file)
    {
        mb_detect_order(['UTF-8', 'UTF-16', 'ISO-8859-1', 'ASCII']);
        $fileContent = file_get_contents($file->getPathname());
        if (mb_detect_encoding($fileContent) !== 'UTF-8') {
            throw new NonUTF8FileException();
        }
    }
}
