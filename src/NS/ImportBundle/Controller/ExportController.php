<?php

namespace NS\ImportBundle\Controller;

use \Doctrine\ORM\QueryBuilder;
use \Exporter\Source\DoctrineORMQuerySourceIterator;
use \NS\SentinelBundle\Entity\IBD;
use \NS\SentinelBundle\Entity\RotaVirus;
use NS\SentinelBundle\Filter\Type\IBD\ReportFilterType as IBDReportFilterType;
use NS\SentinelBundle\Filter\Type\RotaVirus\ReportFilterType as RotaVirusReportFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sonata\CoreBundle\Exporter\Exporter;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

/**
 * Description of ExportController
 *
 * @author gnat
 * @Route("/{_locale}/export")
 */
class ExportController extends Controller
{
    private $baseField = array('region.code','country.code','site.code', 'id');
    private $formParams = array('validation_groups' => array('FieldPopulation'), 'include_filter' => false);

    /**
     * @Route("/",name="exportIndex")
     * @Method(methods={"GET","POST"})
     */
    public function indexAction()
    {
        $ibdForm = $this->createForm(IBDReportFilterType::class, null, $this->formParams);
        $rotaForm = $this->createForm(RotaVirusReportFilterType::class, null, $this->formParams);

        return $this->render('NSImportBundle:Export:index.html.twig', array('ibdForm' => $ibdForm->createView(), 'rotaForm' => $rotaForm->createView()));
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/ibd",name="exportIbd")
     */
    public function exportIBD(Request $request)
    {
        $ibdForm = $this->createForm(IBDReportFilterType::class, null, $this->formParams);
        $ibdForm->handleRequest($request);
        if ($ibdForm->isValid()) {
            $modelManager = $this->get('doctrine.orm.entity_manager');
            $fields = $this->baseField;
            $meta = array(
                "%s" => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\IBD'),
                "siteLab.%s" => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\IBD\SiteLab'),
                "referenceLab.%s" => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\IBD\ReferenceLab'),
                "nationalLab.%s" => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\IBD\NationalLab'),
            );

            $this->adjustFields($meta, $fields);

            $query = $modelManager->getRepository('NSSentinelBundle:IBD')->exportQuery('i');

            return $this->export('csv', $ibdForm, $query, $fields);
        }
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/rota",name="exportRota")
     */
    public function exportRotaVirus(Request $request)
    {
        $rotaForm = $this->createForm(RotaVirusReportFilterType::class, null, $this->formParams);
        $rotaForm->handleRequest($request);
        if ($rotaForm->isValid()) {
            $modelManager = $this->get('doctrine.orm.entity_manager');
            $fields = $this->baseField;
            $meta = array(
                "%s" => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\RotaVirus'),
                "siteLab.%s" => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\RotaVirus\SiteLab'),
                "referenceLab.%s" => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\RotaVirus\ReferenceLab'),
                "nationalLab.%s" => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\RotaVirus\NationalLab'),
            );

            $this->adjustFields($meta, $fields);
            $query = $modelManager->getRepository('NSSentinelBundle:RotaVirus')->exportQuery('i');

            return $this->export('csv', $rotaForm, $query, $fields);
        }
    }

    /**
     *
     * @param array $metas
     * @param array $fields
     */
    private function adjustFields(array $metas, array &$fields)
    {
        foreach ($metas as $sprint => $meta) {
            foreach ($meta->getFieldNames() as $field) {
                if ($field == 'id') {
                    continue;
                }

                $fields[] = sprintf($sprint, $field);
            }
        }
    }

    /**
     *
     * @param string $format
     * @param FormInterface $form
     * @param QueryBuilder $queryBuilder
     * @param array $fields
     * @return Response
     */
    private function export($format, FormInterface $form, QueryBuilder $queryBuilder, array $fields)
    {
        $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $queryBuilder, $queryBuilder->getRootAlias());

        $query = $queryBuilder->getQuery();
        $source = new DoctrineORMQuerySourceIterator($query, $fields);
        $filename = sprintf('export_%s.%s', date('Y_m_d_H_i_s'), $format);

        $exporter = new Exporter();
        return $exporter->getResponse($format, $filename, $source);
    }
}
