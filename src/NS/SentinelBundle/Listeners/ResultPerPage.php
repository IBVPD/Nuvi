<?php

namespace NS\SentinelBundle\Listeners;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

/**
 * Description of ResultPerPage
 *
 * @author gnat
 */
class ResultPerPage
{
    public function onRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType())
            return;

        $request = $event->getRequest();
        if($request->query->has('result_per_page'))
        {
            $request->getSession()->set('result_per_page',$request->query->get('result_per_page'));
            $request->query->remove('result_per_page');
        }

        return;
    }
}
