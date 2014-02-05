<?php

namespace NS\SentinelBundle\Listeners;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use \Symfony\Component\Form\FormFactoryInterface;

/**
 * Description of ResultPerPage
 *
 * @author gnat
 */
class ResultPerPage
{
    private $form;
    private $router;

    public function __construct(\Symfony\Component\Routing\Router $router, FormFactoryInterface $formFactory)
    {
        $this->router = $router;
        $this->form = $formFactory->create('results_per_page');
    }

    public function onRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType())
            return;

        $request = $event->getRequest();

        if($request->request->has($this->form->getName()))
        {
            $this->form->handleRequest($request);

            if(!$this->form->isValid())
                die("FORM IS INVALID! ".$this->form->getErrorsAsString()." Requests ".print_r($request->request->all(),true));

//            die(print_r($this->form->getData(),true));
            $request->getSession()->set('result_per_page',$this->form->get('recordsperpage')->getData());
            $r = new \Symfony\Component\HttpFoundation\RedirectResponse($this->router->generate($this->form->get('target')->getData()));
            $event->setResponse($r);
        }

        return;
    }
}
