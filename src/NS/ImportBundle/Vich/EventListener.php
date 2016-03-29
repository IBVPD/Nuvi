<?php

namespace NS\ImportBundle\Vich;

use NS\ImportBundle\Entity\Import;
use NS\ImportBundle\Reader\ReaderFactory;
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

        if ($obj instanceof Import && $property == 'source' && $this->readerFactory->getExtension($obj->getSourceFile()) == 'csv') {
            $this->convertToUtf8($obj->getSourceFile()->getPathname());
        }
    }

    /**
     * @param $path
     */
    public function convertToUtf8($path)
    {
        $fileContent = file_get_contents($path);
        file_put_contents($path, mb_convert_encoding($fileContent, 'UTF-8'));
    }
}
