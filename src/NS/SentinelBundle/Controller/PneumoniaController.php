<?php

namespace NS\SentinelBundle\Controller;

use NS\SentinelBundle\Entity\Pneumonia\NationalLab;
use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
use NS\SentinelBundle\Entity\Pneumonia\ReferenceLab;
use NS\SentinelBundle\Entity\Pneumonia\SiteLab;
use NS\SentinelBundle\Filter\Type\Pneumonia\FilterType;
use NS\SentinelBundle\Form\Pneumonia\CaseType;
use NS\SentinelBundle\Form\Pneumonia\NationalLabType;
use NS\SentinelBundle\Form\Pneumonia\OutcomeType;
use NS\SentinelBundle\Form\Pneumonia\ReferenceLabType;
use NS\SentinelBundle\Form\Pneumonia\SiteLabType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/{_locale}/pneumonia")
 */
class PneumoniaController extends BaseCaseController
{
    /**
     * @Route("/",name="pneumoniaIndex")
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('NSSentinelBundle:Pneumonia:index.html.twig', $this->index($request, Pneumonia::class, FilterType::class, 'pneumonia.index'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Route("/create",name="pneumoniaCreate")
     * @Method({"POST","GET"})
     * @return Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $returnValue = $this->create($request, Pneumonia::class, 'pneumoniaIndex', 'pneumonia', FilterType::class, 'pneumonia.index');

        if ($returnValue instanceof RedirectResponse) {
            return $returnValue;
        }

        return $this->render('NSSentinelBundle:Pneumonia:index.html.twig', $returnValue);
    }

    /**
     * @Route("/edit/{id}",name="pneumoniaEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, $id = null)
    {
        $response = $this->edit($request, CaseType::class, 'pneumoniaIndex', 'pneumoniaEdit', $id);

        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:Pneumonia:edit.html.twig', $response);
    }

    /**
     * @Route("/delete/{id}",name="pneumoniaDelete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        return $this->delete( CaseType::class, $id, 'pneumoniaIndex');
    }

    /**
     * @Route("/rrl/edit/{id}",name="pneumoniaRRLEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editRRLAction(Request $request, $id = null)
    {
        $response = $this->edit($request, ReferenceLabType::class, 'pneumoniaIndex', 'pneumoniaRRLEdit', $id);
        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:Pneumonia:editBaseLab.html.twig', $response);
    }

    /**
     * @Route("/nl/edit/{id}",name="pneumoniaNLEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editNLAction(Request $request, $id = null)
    {
        $response = $this->edit($request, NationalLabType::class, 'pneumoniaIndex', 'pneumoniaNLEdit', $id);

        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:Pneumonia:editBaseLab.html.twig', $response);
    }

    /**
     * @Route("/lab/edit/{id}",name="pneumoniaLabEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editLabAction(Request $request, $id = null)
    {
        $response = $this->edit($request, SiteLabType::class, 'pneumoniaIndex', 'pneumoniaLabEdit', $id);
        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:Pneumonia:editLab.html.twig', $response);
    }

    /**
     * @Route("/outcome/edit/{id}",name="pneumoniaOutcomeEdit",defaults={"id"=null})
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @param null $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editOutcomeAction(Request $request, $id = null)
    {
        $response = $this->edit($request, OutcomeType::class, 'pneumoniaIndex', 'pneumoniaOutcomeEdit', $id);
        return ($response instanceof Response) ? $response : $this->render('NSSentinelBundle:Pneumonia:editOutcome.html.twig', $response);
    }

    /**
     * @param string $type
     * @param string $objId
     * @param bool $forDelete
     * @return mixed|\NS\SentinelBundle\Entity\Pneumonia|null
     * @throws \Doctrine\ORM\UnexpectedResultException
     */
    protected function getObject($type, $objId, $forDelete = false)
    {
        switch ($type) {
            case CaseType::class:
            case OutcomeType::class:
                if ($forDelete) {
                    return $this->get('doctrine.orm.entity_manager')->getRepository(Pneumonia::class)->findWithAssociations($objId);
                }
                return $this->get('doctrine.orm.entity_manager')->getRepository(Pneumonia::class)->find($objId);
            case SiteLabType::class:
                return $this->get('doctrine.orm.entity_manager')->getRepository(SiteLab::class)->findOrCreateNew($objId);
            case ReferenceLabType::class:
                return $this->get('doctrine.orm.entity_manager')->getRepository(ReferenceLab::class)->findOrCreateNew($objId);
            case NationalLabType::class:
                return $this->get('doctrine.orm.entity_manager')->getRepository(NationalLab::class)->findOrCreateNew($objId);
            default:
                throw new \RuntimeException('Unknown type');
        }
    }

    protected function getCaseRecord($objId)
    {
        return $this->get('doctrine.orm.entity_manager')->getRepository(Pneumonia::class)->findWithAssociations($objId);
    }

    /**
     * @Route("/show/{id}",name="pneumoniaShow")
     * @Method(methods={"GET"})
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        $ret = $this->show(Pneumonia::class, $id);
        if ($ret instanceof Response) {
            return $ret;
        }

        return $this->render('NSSentinelBundle:Pneumonia:show.html.twig', $ret);
    }
}
