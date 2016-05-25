<?php

namespace NS\SentinelBundle\Controller;

use NS\SentinelBundle\Filter\Type\RotaVirus\FilterType;
use NS\SentinelBundle\Form\RotaVirus\CaseType;
use NS\SentinelBundle\Form\RotaVirus\NationalLabType;
use NS\SentinelBundle\Form\RotaVirus\OutcomeType;
use NS\SentinelBundle\Form\RotaVirus\ReferenceLabType;
use NS\SentinelBundle\Form\RotaVirus\SiteLabType;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/{_locale}/rota")
 */
class RotaVirusController extends BaseCaseController
{
    /**
     * @Route("/",name="rotavirusIndex")
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('NSSentinelBundle:RotaVirus:index.html.twig', $this->index($request, 'NSSentinelBundle:RotaVirus', FilterType::class, 'rota.index'));
    }

    /**
     * @param Request $request
     * @Route("/create",name="rotavirusCreate")
     * @Method({"POST"})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        return $this->create($request, 'NSSentinelBundle:RotaVirus', 'rotavirusIndex', 'rotavirus');
    }

    /**
     * @Route("/edit/{id}",name="rotavirusEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, $id = null)
    {
        $response = $this->edit($request, CaseType::class, 'rotavirusIndex', 'rotavirusEdit', $id);
        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:RotaVirus:edit.html.twig', $response);
    }

    /**
     * @Route("/delete/{id}",name="rotavirusDelete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        return $this->delete(CaseType::class, $id, 'rotavirusIndex');
    }

    /**
     * @Route("/lab/edit/{id}",name="rotavirusLabEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editLabAction(Request $request, $id = null)
    {
        $response = $this->edit($request, SiteLabType::class, 'rotavirusIndex', 'rotavirusLabEdit', $id);

        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:RotaVirus:editLab.html.twig', $response);
    }

    /**
     * @Route("/rrl/edit/{id}",name="rotavirusRRLEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editRRLAction(Request $request, $id = null)
    {
        $response = $this->edit($request, ReferenceLabType::class, 'rotavirusIndex', 'rotavirusRRLEdit', $id);
        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:RotaVirus:editBaseLab.html.twig', $response);
    }

    /**
     * @Route("/nl/edit/{id}",name="rotavirusNLEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editNLAction(Request $request, $id = null)
    {
        $response = $this->edit($request, NationalLabType::class, 'rotavirusIndex', 'rotavirusNLEdit', $id);
        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:RotaVirus:editBaseLab.html.twig', $response);
    }

    /**
     * @Route("/outcome/edit/{id}",name="rotavirusOutcomeEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editOutcomeAction(Request $request, $id = null)
    {
        $response = $this->edit($request, OutcomeType::class, 'rotavirusIndex', 'rotavirusOutcomeEdit', $id);
        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:RotaVirus:editOutcome.html.twig', $response);
    }

    protected function getCaseRecord($objId)
    {
        return $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:RotaVirus')->findWithAssociations($objId);
    }

    protected function getObject($type, $objId, $forDelete = false)
    {
        switch ($type) {
            case CaseType::class:
            case OutcomeType::class:
                if($forDelete) {
                    return $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:RotaVirus')->findWithAssociations($objId);
                }

                return $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:RotaVirus')->find($objId);

            case SiteLabType::class:
                return $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:RotaVirus\SiteLab')->findOrCreateNew($objId);

            case ReferenceLabType::class:
                return $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:RotaVirus\ReferenceLab')->findOrCreateNew($objId);

            case NationalLabType::class:
                return $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:RotaVirus\NationalLab')->findOrCreateNew($objId);

            default:
                throw new \Exception("Unknown type");
        }
    }

    /**
     * @Route("/show/{id}",name="rotavirusShow")
     * @Method(methods={"GET"})
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $ret = $this->show('NSSentinelBundle:RotaVirus', $id);
        if ($ret instanceof Response) {
            return $ret;
        }

        return $this->render('NSSentinelBundle:RotaVirus:show.html.twig', $ret);
    }
}
