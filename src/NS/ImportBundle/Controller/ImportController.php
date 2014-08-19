<?php

namespace NS\ImportBundle\Controller;

use Ddeboer\DataImport\Reader\CsvReader;
use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Writer\ArrayWriter;
use Exporter\Source\ArraySourceIterator;
use NS\ImportBundle\Filter\Duplicate;
use NS\ImportBundle\Writer\DoctrineWriter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of ImportController
 *
 * @author gnat
 * @Route("/import")
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

        if($form->isValid())
        {
            $importer = $this->get('ns_import.processor');
            $importer->process($form->getData());

//            $results = $doctrineWriter->getResults();

//            $format   = 'csv';
//            $source   = new ArraySourceIterator($results->toArray(),array('id','caseId','site','country', 'region'));
//            $filename = sprintf('export_%s.%s',date('Y_m_d_H_i_s'), $format);
//
//            return $this->get('sonata.admin.exporter')->getResponse($format, $filename, $source);
//            $exporter = $this->get('');
//            die($c->getTotalProcessedCount()." Rows Processed <pre>".print_r(array_keys($output[0]),true)."</pre>");
        }

        return array('form'=>$form->createView());
    }
}
