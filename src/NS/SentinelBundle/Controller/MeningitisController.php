<?php

namespace NS\SentinelBundle\Controller;

use Doctrine\ORM\UnexpectedResultException;
use NS\SentinelBundle\Entity\Meningitis\Meningitis;
use NS\SentinelBundle\Entity\Meningitis\NationalLab;
use NS\SentinelBundle\Entity\Meningitis\ReferenceLab;
use NS\SentinelBundle\Entity\Meningitis\SiteLab;
use NS\SentinelBundle\Filter\Type\Meningitis\FilterType;
use NS\SentinelBundle\Form\Meningitis\CaseType;
use NS\SentinelBundle\Form\Meningitis\NationalLabType;
use NS\SentinelBundle\Form\Meningitis\OutcomeType;
use NS\SentinelBundle\Form\Meningitis\ReferenceLabType;
use NS\SentinelBundle\Form\Meningitis\SiteLabType;
use RuntimeException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/{_locale}/meningitis")
 */
class MeningitisController extends BaseCaseController
{
    /**
     * @Route("/",name="meningitisIndex")
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        return $this->render('NSSentinelBundle:Meningitis:index.html.twig', $this->index($request, Meningitis::class, FilterType::class, 'meningitis.index'));
    }

    /**
     * @param Request $request
     * @Route("/create",name="meningitisCreate")
     * @Method({"POST","GET"})
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request): Response
    {
        $returnValue = $this->create($request, Meningitis::class, 'meningitisIndex', 'meningitis', FilterType::class, 'meningitis.index');

        if ($returnValue instanceof RedirectResponse) {
            return $returnValue;
        }

        return $this->render('NSSentinelBundle:Meningitis:index.html.twig', $returnValue);
    }

    /**
     * @Route("/edit/{id}",name="meningitisEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null $id
     *
     * @return array|RedirectResponse|Response
     */
    public function editAction(Request $request, $id = null): Response
    {
        $response = $this->edit($request, CaseType::class, 'meningitisIndex', 'meningitisEdit', $id);

        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:Meningitis:edit.html.twig', $response);
    }

    /**
     * @Route("/delete/{id}",name="meningitisDelete")
     * @param $id
     *
     * @return RedirectResponse
     */
    public function deleteAction($id): RedirectResponse
    {
        return $this->delete( CaseType::class, $id, 'meningitisIndex');
    }

    /**
     * @Route("/rrl/edit/{id}",name="meningitisRRLEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null $id
     *
     * @return RedirectResponse|Response
     */
    public function editRRLAction(Request $request, $id = null): Response
    {
        $response = $this->edit($request, ReferenceLabType::class, 'meningitisIndex', 'meningitisRRLEdit', $id);
        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:Meningitis:editBaseLab.html.twig', $response);
    }

    /**
     * @Route("/nl/edit/{id}",name="meningitisNLEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null    $id
     *
     * @return RedirectResponse|Response
     */
    public function editNLAction(Request $request, $id = null): Response
    {
        $response = $this->edit($request, NationalLabType::class, 'meningitisIndex', 'meningitisNLEdit', $id);

        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:Meningitis:editBaseLab.html.twig', $response);
    }

    /**
     * @Route("/lab/edit/{id}",name="meningitisLabEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null    $id
     *
     * @return RedirectResponse|Response
     */
    public function editLabAction(Request $request, $id = null): Response
    {
        $response = $this->edit($request, SiteLabType::class, 'meningitisIndex', 'meningitisLabEdit', $id);
        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:Meningitis:editLab.html.twig', $response);
    }

    /**
     * @Route("/outcome/edit/{id}",name="meningitisOutcomeEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null    $id
     *
     * @return RedirectResponse|Response
     */
    public function editOutcomeAction(Request $request, $id = null): Response
    {
        $response = $this->edit($request, OutcomeType::class, 'meningitisIndex', 'meningitisOutcomeEdit', $id);
        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:Meningitis:editOutcome.html.twig', $response);
    }

    /**
     * @param string $type
     * @param string $objId
     * @param bool $forDelete
     * @return mixed|Meningitis|SiteLab|ReferenceLab|NationalLab|null
     * @throws UnexpectedResultException
     *
     */
    protected function getObject($type, $objId, $forDelete = false)
    {
        switch ($type) {
            case CaseType::class:
            case OutcomeType::class:
                if($forDelete) {
                    return $this->get('doctrine.orm.entity_manager')->getRepository(Meningitis::class)->findWithAssociations($objId);
                }
                return $this->get('doctrine.orm.entity_manager')->getRepository(Meningitis::class)->find($objId);
            case SiteLabType::class:
                return $this->get('doctrine.orm.entity_manager')->getRepository(SiteLab::class)->findOrCreateNew($objId);
            case ReferenceLabType::class:
                return $this->get('doctrine.orm.entity_manager')->getRepository(ReferenceLab::class)->findOrCreateNew($objId);
            case NationalLabType::class:
                return $this->get('doctrine.orm.entity_manager')->getRepository(NationalLab::class)->findOrCreateNew($objId);
            default:
                throw new RuntimeException('Unknown type');
        }
    }

    protected function getCaseRecord($objId)
    {
        return $this->get('doctrine.orm.entity_manager')->getRepository(Meningitis::class)->findWithAssociations($objId);
    }

    /**
     * @Route("/show/{id}",name="meningitisShow")
     * @Method(methods={"GET"})
     * @param $id
     * @return Response
     */
    public function showAction($id): Response
    {
        $ret = $this->show(Meningitis::class, $id);
        if ($ret instanceof Response) {
            return $ret;
        }

        return $this->render('NSSentinelBundle:Meningitis:show.html.twig', $ret);
    }
}
