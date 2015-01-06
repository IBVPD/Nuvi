<?php

namespace NS\ImportBundle\Controller;

use \Exporter\Source\DoctrineORMQuerySourceIterator;
use \NS\SentinelBundle\Entity\IBD;
use \NS\SentinelBundle\Entity\RotaVirus;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Request;

/**
 * Description of ExportController
 *
 * @author gnat
 * @Route("/export")
 */
class ExportController extends Controller
{

    /**
     * @Route("/",name="exportIndex")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $alias  = 'i';
        $params = array('validation_groups' => array('FieldPopulation'), 'include_filter' => false);

        $ibdForm = $this->createForm('IBDReportFilterType', null, $params);
        $ibdForm->handleRequest($request);
        if ($ibdForm->isValid())
        {
            $obj    = new IBD();
            $fields = array_merge(array('id', 'site.name', 'country.name', 'region.name'), $obj->getMinimumRequiredFields());
            $query  = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:IBD')->exportQuery($alias);

            return $this->export('xls', $ibdForm, $query, $fields);
        }

        $rotaForm = $this->createForm('RotaVirusReportFilterType', null, $params);
        $rotaForm->handleRequest($request);
        if ($rotaForm->isValid())
        {
            $obj    = new RotaVirus();
            $fields = array_merge(array('id', 'site.name', 'country.name', 'region.name'), $obj->getMinimumRequiredFields());
            $query  = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:RotaVirus')->exportQuery($alias);

            return $this->export('xls', $rotaForm, $query, $fields);
        }

        return array('ibdForm' => $ibdForm->createView(), 'rotaForm' => $rotaForm->createView());
    }

    public function export($format, $form, $query, $fields)
    {
        $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $query, $query->getRootAlias());
        $source   = new DoctrineORMQuerySourceIterator($query->getQuery(), $fields);
        $filename = sprintf('export_%s.%s', date('Y_m_d_H_i_s'), $format);

        return $this->get('sonata.admin.exporter')->getResponse($format, $filename, $source);
    }
}