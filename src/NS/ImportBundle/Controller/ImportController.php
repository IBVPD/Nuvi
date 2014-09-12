<?php

namespace NS\ImportBundle\Controller;

use \Exporter\Source\ArraySourceIterator;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Request;

/**
 * Description of ImportController
 *
 * @author gnat
 * @Route("/{_locale}/import")
 */
class ImportController extends Controller
{
    /**
     * @param Request $request
     * @Route("/",name="importIndex")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm('ImportSelect');

        $form->handleRequest($request);
        $session   = $request->getSession();

        if($form->isValid())
        {
            $importer   = $this->get('ns_import.processor');
            $import     = $form->getData();
            $result     = $importer->process($import);

            $successful = array();
            foreach($result->getResults() as $r)
                $successful[] = array('id'=>$r->getId(),'caseId'=>$r->getCaseId(),'site'=>$r->getSite()->getCode(),'siteName' => $r->getSite()->getName());

            $errors = array();
            foreach($result->getExceptions() as $row => $e)
            {
                $msg = ($e->getPrevious()) ? sprintf("%s - %s",$e->getMessage(),$e->getPrevious()->getMessage()):$e->getMessage();
                $errors[$row] = array('row' => $row,'message' => $msg);
            }

            $result->setExceptions(array());
            $result->setResults(array());

            $timestamp = $result->getEndtime()->format('Y-m-d\TH:i:s');

            $current = array($timestamp => array('success' => $successful, 'errors' => $errors, 'map' => $import->getMap(), 'result' => $result, 'file'=>$import->getFile()->getClientOriginalName()));
            $var     = $session->has('import/results') ? array_merge($session->get('import/results'),$current):$current;
            $session->set('import/results',$var);

            return $this->redirect($this->generateUrl('importIndex'));
        }

        $recent = array();
        if($session->has('import/results'))
            $recent = $session->get('import/results');

        return array('form'=>$form->createView(), 'recent'=>$recent);
    }

    /**
     * @Route("/download/{type}/{timestamp}",name="importResultDownload")
     */
    public function resultDownloadAction(Request $request, $type, $timestamp)
    {
        $res = $request->getSession()->get('import/results');
        if(isset($res[$timestamp][$type]))
        {
            $format = 'csv';
            $output = $res[$timestamp][$type];

            if($type == 'success')
                $source = new ArraySourceIterator($output,array('id','caseId','site','siteName'));
            else if($type == 'errors')
                $source = new ArraySourceIterator($output,array('row','message'));

            $filename = sprintf('export_%s.%s',date('Y_m_d_H_i_s'), $format);

            return $this->get('sonata.admin.exporter')->getResponse($format, $filename, $source);
        }

        throw $this->createNotFoundException();
    }
}
