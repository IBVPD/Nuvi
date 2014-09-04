<?php

namespace NS\ImportBundle\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
        $format  = 'xls';
        $ibdForm = $this->createForm('IBDReportFilterType',null,array('validation_groups'=> array('FieldPopulation'),'include_filter'=>false));
        $ibdForm->handleRequest($request);
        if($ibdForm->isValid())
        {
            $alias  = 'i';
            $fields = array('id','site.name','country.name','region.name');
            $query  = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:IBD')->exportQuery($alias);

            return $this->export($format, $ibdForm, $query, $fields);
        }

        $rotaForm = $this->createForm('RotaVirusReportFilterType',null,array('validation_groups'=> array('FieldPopulation'),'include_filter'=>false));
        $rotaForm->handleRequest($request);
        if($rotaForm->isValid())
        {
            $alias  = 'i';
            $fields = array('id','site.name','country.name','region.name');
            $query  = $this->get('ns.model_manager')->getRepository('NSSentinelBundle:RotaVirus')->exportQuery($alias);

            return $this->export($format, $ibdForm, $query, $fields);
        }

        return array( 'ibdForm' => $ibdForm->createView(), 'rotaForm'=>$rotaForm->createView() );
    }

    public function export($format, $form, $query, $fields)
    {
        $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $query, $query->getRootAlias());
        $source   = new \Exporter\Source\DoctrineORMQuerySourceIterator($query->getQuery(),$fields);
        $filename = sprintf('export_%s.%s',date('Y_m_d_H_i_s'), $format);

        return $this->get('sonata.admin.exporter')->getResponse($format, $filename, $source);
    }
}
