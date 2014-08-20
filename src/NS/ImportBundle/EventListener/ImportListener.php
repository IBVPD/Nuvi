<?php

namespace NS\ImportBundle\EventListener;

use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Description of ImportListener
 *
 * @author gnat
 */
class ImportListener implements EventSubscriberInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        $listeners = array(KernelEvents::TERMINATE => 'onTerminate');

        if (class_exists('Symfony\Component\Console\ConsoleEvents')) {
            $listeners[ConsoleEvents::TERMINATE] = 'onTerminate';
        }

        return $listeners;
    }

    public function onTerminate()
    {

    }
}
