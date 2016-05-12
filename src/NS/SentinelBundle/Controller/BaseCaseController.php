<?php

namespace NS\SentinelBundle\Controller;

use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\FilteredPaginationBundle\Form\Type\LimitSelectType;
use \NS\SentinelBundle\Entity\BaseExternalLab;
use NS\SentinelBundle\Entity\ReferenceLabResultInterface;
use \NS\SentinelBundle\Exceptions\NonExistentCaseException;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Request;

/**
 * Description of BaseCaseController
 *
 * @author gnat
 */
abstract class BaseCaseController extends Controller implements TranslationContainerInterface
{
    /**
     * @param Request $request
     * @param $class
     * @param $filterFormName
     * @param $sessionKey
     * @return array
     */
    protected function index(Request $request, $class, $filterFormName, $sessionKey)
    {
        $query = $this->get('doctrine.orm.entity_manager')
            ->getRepository($class)
            ->getFilterQueryBuilder();

        $filteredPager = $this->get('ns.filtered_pagination');
        list($filterForm, $pagination) = $filteredPager->process($request, $filterFormName, $query, $sessionKey);
        $createForm = ($this->get('security.authorization_checker')->isGranted('ROLE_CAN_CREATE')) ? $this->createForm('NS\SentinelBundle\Form\CreateType')->createView() : null;

        return array(
            'pagination' => $pagination,
            'limitForm'  => $this->createForm(new LimitSelectType(), array('limit'=>$filteredPager->getPerPage()))->createView(),
            'filterForm' => $filterForm->createView(),
            'createForm' => $createForm);
    }

    /**
     * @param Request $request
     * @param $class
     * @param $indexRoute
     * @param $typeName
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function create(Request $request, $class, $indexRoute, $typeName)
    {
        $form = $this->createForm('NS\SentinelBundle\Form\CreateType');
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

    /**
     * @param string $type
     * @param string|null $objId
     * @return \Symfony\Component\Form\Form
     * @throws \Doctrine\ORM\UnexpectedResultException
     * @throws \Exception
     * @throws \NS\SentinelBundle\Exceptions\NonExistentCaseException
     */
    protected function getForm($type, $objId = null)
    {
        return $this->createForm($type, ($objId)?$this->getObject($type, $objId):null);
    }

    abstract protected function getCaseRecord($objId);
    abstract protected function getObject($type, $objId, $forDelete = false);

    /**
     * @param string $type
     * @param string $objId
     * @param string $redirectRoute
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function delete($type, $objId, $redirectRoute)
    {
        $record = $this->getObject($type, $objId, true);
        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $entityMgr->remove($record);
        $entityMgr->flush();
        $this->get('ns_flash')->addSuccess('Success','Case removed successfully!');

        return $this->redirect($this->generateUrl($redirectRoute));
    }

    /**
     * @param Request $request
     * @param $type
     * @param $indexRoute
     * @param $editRoute
     * @param null $objId
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function edit(Request $request, $type, $indexRoute, $editRoute, $objId = null)
    {
        try {
            $form = $this->getForm($type, $objId);
        } catch (NonExistentCaseException $ex) {
            return $this->render('NSSentinelBundle:User:unknownCase.html.twig', array('message' => $ex->getMessage()));
        }

        $form->handleRequest($request);
        if ($form->isValid()) {
            $entityMgr = $this->get('doctrine.orm.entity_manager');
            $record = $form->getData();

            if ($record instanceof ReferenceLabResultInterface && $this->getUser()->hasReferenceLab()) {
                $record->setLab($entityMgr->getReference('NSSentinelBundle:ReferenceLab', $this->getUser()->getReferenceLab()->getId()));
            }

            $entityMgr->persist($record);
            $entityMgr->flush();

            $this->get('ns_flash')->addSuccess('Success!', null, 'Case edited successfully');

            if ($request->request->has('saveclose')) {
                return $this->redirect($this->generateUrl($indexRoute));
            }

            return $this->redirect($this->generateUrl($editRoute, array('id'=>$objId)));
        } elseif ($form->isSubmitted()) {
            $this->get('ns_flash')->addWarning('Warning!', 'There were errors with saving the form.', 'Please review each tab for error messages');
        }

        $record = $this->getCaseRecord($objId);

        return array('form' => $form->createView(), 'id' => $objId, 'editRoute' => $editRoute,'record' => $record);
    }

    /**
     * @param $class
     * @param $id
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    protected function show($class, $id)
    {
        try {
            return array('record' => $this->get('doctrine.orm.entity_manager')->getRepository($class)->get($id));
        } catch (NonExistentCaseException $ex) {
            return $this->render('NSSentinelBundle:User:unknownCase.html.twig', array('message' => $ex->getMessage()));
        }
    }

    /**
     * @inheritDoc
     */
    static function getTranslationMessages()
    {
        return array(
            new Message('Warning!'),
            new Message('There were errors with saving the form.'),
            new Message('Please review each tab for error messages'),
            new Message('Success!'),
            new Message('Case edited successfully'),
        );
    }
}
