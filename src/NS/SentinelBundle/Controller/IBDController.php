<?php

namespace NS\SentinelBundle\Controller;

use NS\SentinelBundle\Filter\Type\IBD\FilterType;
use NS\SentinelBundle\Form\IBD\CaseType;
use NS\SentinelBundle\Form\IBD\NationalLabType;
use NS\SentinelBundle\Form\IBD\OutcomeType;
use NS\SentinelBundle\Form\IBD\ReferenceLabType;
use NS\SentinelBundle\Form\IBD\SiteLabType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/{_locale}/ibd")
 */
class IBDController extends BaseCaseController
{
    /**
     * @Route("/",name="ibdIndex")
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('NSSentinelBundle:IBD:index.html.twig', $this->index($request, 'NSSentinelBundle:IBD', FilterType::class, 'ibd.index'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Route("/create",name="ibdCreate")
     * @Method({"POST","GET"})
     * @return Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $returnValue = $this->create($request, 'NSSentinelBundle:IBD', 'ibdIndex', 'ibd', FilterType::class, 'ibd.index');

        if ($returnValue instanceof RedirectResponse) {
            return $returnValue;
        }

        return $this->render('NSSentinelBundle:IBD:index.html.twig', $returnValue);
    }

    /**
     * @Route("/edit/{id}",name="ibdEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, $id = null)
    {
        $response = $this->edit($request, CaseType::class, 'ibdIndex', 'ibdEdit', $id);

        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:IBD:edit.html.twig', $response);
    }

    /**
     * @Route("/delete/{id}",name="ibdDelete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        return $this->delete( CaseType::class, $id, 'ibdIndex');
    }

    /**
     * @Route("/rrl/edit/{id}",name="ibdRRLEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editRRLAction(Request $request, $id = null)
    {
        $response = $this->edit($request, ReferenceLabType::class, 'ibdIndex', 'ibdRRLEdit', $id);
        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:IBD:editBaseLab.html.twig', $response);
    }

    /**
     * @Route("/nl/edit/{id}",name="ibdNLEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editNLAction(Request $request, $id = null)
    {
        $response = $this->edit($request, NationalLabType::class, 'ibdIndex', 'ibdNLEdit', $id);

        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:IBD:editBaseLab.html.twig', $response);
    }

    /**
     * @Route("/lab/edit/{id}",name="ibdLabEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editLabAction(Request $request, $id = null)
    {
        $response = $this->edit($request, SiteLabType::class, 'ibdIndex', 'ibdLabEdit', $id);
        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:IBD:editLab.html.twig', $response);
    }

    /**
     * @Route("/outcome/edit/{id}",name="ibdOutcomeEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editOutcomeAction(Request $request, $id = null)
    {
        $response = $this->edit($request, OutcomeType::class, 'ibdIndex', 'ibdOutcomeEdit', $id);
        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:IBD:editOutcome.html.twig', $response);
    }

    /**
     * @param string $type
     * @param string $objId
     * @param bool $forDelete
     * @return mixed|\NS\SentinelBundle\Entity\IBD|null
     * @throws \Doctrine\ORM\UnexpectedResultException
     */
    protected function getObject($type, $objId, $forDelete = false)
    {
        switch ($type) {
            case CaseType::class:
            case OutcomeType::class:
                if($forDelete) {
                    return $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:IBD')->findWithAssociations($objId);
                }
                return $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:IBD')->find($objId);
            case SiteLabType::class:
                return $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:IBD\SiteLab')->findOrCreateNew($objId);
            case ReferenceLabType::class:
                return $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:IBD\ReferenceLab')->findOrCreateNew($objId);
            case NationalLabType::class:
                return $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:IBD\NationalLab')->findOrCreateNew($objId);
            default:
                throw new \RuntimeException("Unknown type");
        }
    }

    protected function getCaseRecord($objId)
    {
        return $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:IBD')->findWithAssociations($objId);
    }

    /**
     * @Route("/show/{id}",name="ibdShow")
     * @Method(methods={"GET"})
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $ret = $this->show('NSSentinelBundle:IBD', $id);
        if ($ret instanceof Response) {
            return $ret;
        }

        return $this->render('NSSentinelBundle:IBD:show.html.twig', $ret);
    }
}
