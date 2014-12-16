<?php

namespace NS\SentinelBundle\Listeners;

use \Symfony\Component\Form\FormFactoryInterface;
use \Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpKernel\Event\GetResponseEvent;
use \Symfony\Component\HttpKernel\HttpKernel;
use \Symfony\Component\Routing\Router;

/**
 * Description of ResultPerPage
 *
 * @author gnat
 */
class ResultPerPage
{
    private $formFactory;
    private $router;

    /**
     * @param Router $router
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(Router $router, FormFactoryInterface $formFactory)
    {
        $this->router = $router;
        $this->formFactory = $formFactory;
    }

    /**
     * @param GetResponseEvent $event
     * @return null
     */
    public function onRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType())
            return;

        $request = $event->getRequest();

        $form = $this->formFactory->create('results_per_page');
        if($request->request->has($form->getName()))
        {
            $form->handleRequest($request);
            $request->getSession()->set('result_per_page', $form->get('recordsperpage')->getData());
            $response = new RedirectResponse($this->router->generate($form->get('target')->getData(), $request->query->all()));
            $event->setResponse($response);
        }

        return;
    }
}
