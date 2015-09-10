<?php

namespace NS\SentinelBundle\Controller;

use \NS\SentinelBundle\Entity\BaseExternalLab;
use \NS\SentinelBundle\Exceptions\NonExistentCase;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Request;

/**
 * Description of BaseCaseController
 *
 * @author gnat
 */
abstract class BaseCaseController extends Controller
{

    protected function index(Request $request, $class, $filterFormName)
    {
        $filterForm = $this->createForm($filterFormName);
        $filterForm->handleRequest($request);

        if ($filterForm->isValid()) {
            $query = $this->get('doctrine.orm.entity_manager')
                ->getRepository($class)
                ->getFilterQueryBuilder();

            // build the query from the given form object
            $queryBuilderUpdater = $this->get('lexik_form_filter.query_builder_updater');
            $queryBuilderUpdater->addFilterConditions($filterForm, $query, 'm');
        } else {
            $query = $this->get('doctrine.orm.entity_manager')->getRepository($class)->getLatestQuery();
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->get('page', 1), $request->getSession()->get('result_per_page', 10));
        $createForm = ($this->get('security.authorization_checker')->isGranted('ROLE_CAN_CREATE')) ? $this->createForm('create_case')->createView() : null;

        return array(
            'pagination' => $pagination,
            'form' => $this->createForm('results_per_page')->createView(),
            'filterForm' => $filterForm->createView(),
            'createForm' => $createForm);
    }

    protected function create(Request $request, $class, $indexRoute, $typeName)
    {
        $form = $this->createForm('create_case');
        $form->handleRequest($request);

        if ($form->isValid()) {
            $caseId    = $form->get('caseId')->getData();
            $type      = $form->get('type')->getData();
            $entityMgr = $this->get('doctrine.orm.entity_manager');
            $case = $entityMgr->getRepository($class)->findOrCreate($caseId);

            if (!$case->getId()) {
                $site = ($form->has('site')) ? $form->get('site')->getData() : $this->get('ns.sentinel.sites')->getSite();
                $case->setSite($site);
            }

            $entityMgr->persist($case);
            $entityMgr->flush();

            return $this->redirect($this->generateUrl($type->getRoute($typeName), array('id' => $case->getId())));
        }

        return $this->redirect($this->generateUrl($indexRoute));
    }

    abstract protected function getForm($type, $objId = null);

    protected function edit(Request $request, $type, $indexRoute, $editRoute, $objId = null)
    {
        try {
            $form = $this->getForm($type, $objId);
        } catch (NonExistentCase $ex) {
            return $this->render('NSSentinelBundle:User:unknownCase.html.twig', array('message' => $ex->getMessage()));
        }

        $form->handleRequest($request);
        if ($form->isValid()) {
            $entityMgr = $this->get('doctrine.orm.entity_manager');
            $record = $form->getData();

            if ($record instanceof BaseExternalLab && $this->getUser()->hasReferenceLab()) {
                $record->setLab($entityMgr->getReference('NSSentinelBundle:ReferenceLab', $this->getUser()->getReferenceLab()->getId()));
            }

            $entityMgr->persist($record);
            $entityMgr->flush();

            $this->get('ns_flash')->addSuccess('Success!',null,'Case edited successfully');
            return $this->redirect($this->generateUrl($indexRoute));
        }

        return array('form' => $form->createView(), 'id' => $objId, 'editRoute' => $editRoute);
    }

    protected function show($class, $id)
    {
        try {
            return array('record' => $this->get('doctrine.orm.entity_manager')->getRepository($class)->get($id));
        } catch (NonExistentCase $ex) {
            return $this->render('NSSentinelBundle:User:unknownCase.html.twig', array(
                'message' => $ex->getMessage()));
        }
    }

}
