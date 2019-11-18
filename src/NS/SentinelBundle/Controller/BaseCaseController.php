<?php

namespace NS\SentinelBundle\Controller;

use DateTime;
use Doctrine\ORM\UnexpectedResultException;
use Exception;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\FilteredPaginationBundle\Form\Type\LimitSelectType;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\ReferenceLabResultInterface;
use NS\SentinelBundle\Exceptions\NonExistentCaseException;
use NS\SentinelBundle\Form\CreateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class BaseCaseController extends Controller implements TranslationContainerInterface
{
    /**
     * @param Request $request
     * @param         $class
     * @param         $filterFormName
     * @param         $sessionKey
     *
     * @return array
     */
    protected function index(Request $request, $class, $filterFormName, $sessionKey): array
    {
        $query = $this->get('doctrine.orm.entity_manager')
            ->getRepository($class)
            ->getFilterQueryBuilder();

        $filteredPager = $this->get('ns.filtered_pagination');
        $filterData    = $request->query->get('filter', false);

        if (isset($filterData['find']) || (!isset($filterData['reset']) && !empty($request->getSession()->get($sessionKey, [])))) {
            list($filterForm, $pagination) = $filteredPager->process($request, $filterFormName, $query, $sessionKey);
        } else {
            list($filterForm, $pagination) = $filteredPager->handleForm($request, $filterFormName, $sessionKey);
        }

        $createForm = $this->get('security.authorization_checker')->isGranted('ROLE_CAN_CREATE') ? $this->createForm(CreateType::class)->createView() : null;

        return [
            'pagination' => $pagination,
            'limitForm' => $this->createForm(LimitSelectType::class, ['limit' => $filteredPager->getPerPage()])->createView(),
            'filterForm' => $filterForm->createView(),
            'createForm' => $createForm];
    }

    /**
     * @param Request $request
     * @param         $class
     * @param         $indexRoute
     * @param         $typeName
     * @param         $filterFormName
     * @param         $sessionKey
     *
     * @return array|RedirectResponse
     */
    protected function create(Request $request, $class, $indexRoute, $typeName, $filterFormName, $sessionKey)
    {
        $form = $this->createForm(CreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $caseId = $form->get('caseId')->getData();
                $type   = $form->get('type')->getData();
                $site   = $form->has('site') ? $form->get('site')->getData() : $this->get('ns.sentinel.sites')->getSite();

                $entityMgr = $this->get('doctrine.orm.entity_manager');
                $case      = $entityMgr->getRepository($class)->findOrCreate($caseId, $site);

                if (!$case->getId()) {
                    $case->setSite($site);
                }

                $entityMgr->persist($case);
                $entityMgr->flush();

                return $this->redirect($this->generateUrl($type->getRoute($typeName), ['id' => $case->getId()]));
            }

            $params               = $this->index($request, $class, $filterFormName, $sessionKey);
            $params['createForm'] = $form->createView();
            return $params;
        }

        return $this->redirect($this->generateUrl($indexRoute));
    }

    /**
     * @param string      $type
     * @param string|null $objId
     *
     * @return Form
     * @throws Exception
     * @throws NonExistentCaseException
     */
    protected function getForm($type, $objId = null): Form
    {
        return $this->createForm($type, $objId ? $this->getObject($type, $objId) : null);
    }

    abstract protected function getCaseRecord($objId);

    abstract protected function getObject($type, $objId, $forDelete = false);

    /**
     * @param string $type
     * @param string $objId
     * @param string $redirectRoute
     *
     * @return RedirectResponse
     */
    protected function delete($type, $objId, $redirectRoute): RedirectResponse
    {
        $record = $this->getObject($type, $objId, true);
        if (!$record) {
            throw new NotFoundHttpException("Unable to locate case $objId");
        }

        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $entityMgr->remove($record);
        $entityMgr->flush();
        $this->get('ns_flash')->addSuccess('Success', 'Case removed successfully!');

        return $this->redirect($this->generateUrl($redirectRoute));
    }

    /**
     * @param Request $request
     * @param         $type
     * @param         $indexRoute
     * @param         $editRoute
     * @param null    $objId
     *
     * @return array|RedirectResponse|Response
     */
    protected function edit(Request $request, $type, $indexRoute, $editRoute, $objId = null)
    {
        try {
            $form = $this->getForm($type, $objId);
        } catch (NonExistentCaseException $ex) {
            return $this->render('NSSentinelBundle:User:unknownCase.html.twig', ['message' => $ex->getMessage()]);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityMgr = $this->get('doctrine.orm.entity_manager');
            $record    = $form->getData();

            if ($record instanceof ReferenceLabResultInterface && $this->getUser()->hasReferenceLab()) {
                $record->setLab($entityMgr->getReference('NSSentinelBundle:ReferenceLab', $this->getUser()->getReferenceLab()->getId()));
            }

            $entityMgr->persist($record);
            $entityMgr->flush();

            $this->get('ns_flash')->addSuccess('Success!', null, 'Case edited successfully');

            if ($request->request->has('saveclose')) {
                return $this->redirect($this->generateUrl($indexRoute));
            }

            return $this->redirect($this->generateUrl($editRoute, ['id' => $objId]));
        }

        if ($form->isSubmitted()) {
            $this->get('ns_flash')->addWarning('Warning!', 'There were errors with saving the form.', 'Please review each tab for error messages');
        }

        $record = $this->getCaseRecord($objId);

        return ['form' => $form->createView(), 'id' => $objId, 'editRoute' => $editRoute, 'record' => $record];
    }

    /**
     * @param $class
     * @param $id
     *
     * @return array|Response
     */
    protected function show($class, $id)
    {
        try {
            return ['record' => $this->get('doctrine.orm.entity_manager')->getRepository($class)->get($id)];
        } catch (NonExistentCaseException $ex) {
            return $this->render('NSSentinelBundle:User:unknownCase.html.twig', ['message' => $ex->getMessage()]);
        }
    }

    protected function checkDuplicates(Request $request, string $class): JsonResponse
    {
        $caseId     = $request->request->get('caseId', false);
        $siteId     = $request->request->get('siteId', false);
        $admDateStr = $request->request->get('admDate', false);
        $dobDateStr = $request->request->get('dobDate', false);

        if ($caseId === false || $siteId === false || $admDateStr === false || $dobDateStr === false) {
            return new JsonResponse(['Missing parameters'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $adminDate = new DateTime($admDateStr);
            $dobDate   = new DateTime($dobDateStr);
        } catch (Exception $e) {
            return new JsonResponse(['Missing parameters'], Response::HTTP_BAD_REQUEST);
        }

        /** @var BaseCase[] $cases */
        $cases = $this->get('doctrine.orm.entity_manager')->getRepository($class)->getPotentialDuplicates($caseId, $siteId, $adminDate, $dobDate);
        $output = [];
        foreach ($cases as $case) {
            $output[] = [
                'caseId'    => $case->getCaseId(),
                'lastName'  => $case->getLastName(),
                'firstName' => $case->getFirstName(),
                'district'  => $case->getDistrict(),
                'dob'       => $case->getDob()->format('Y-m-d'),
                'admDate'   => $case->getAdmDate()->format('Y-m-d'),
            ];
        }

        return new JsonResponse($output);
    }

    public static function getTranslationMessages(): array
    {
        return [
            new Message('Warning!'),
            new Message('There were errors with saving the form.'),
            new Message('Please review each tab for error messages'),
            new Message('Success!'),
            new Message('Case edited successfully'),
        ];
    }
}
