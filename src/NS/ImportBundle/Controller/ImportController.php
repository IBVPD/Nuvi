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
            $em     = $this->get('doctrine.orm.entity_manager');
            $import = $form->getData();
            $map    = $import->getMap();

            // Create and configure the reader
            $csvReader = new CsvReader($import->getFile()->openFile(),',');

            // Tell the reader that the first row in the CSV file contains column headers
            $csvReader->setHeaderRowNumber(0);
//            $csvReader->setColumnHeaders($map->getColumnHeaders());

            // Create the workflow from the reader
            $workflow = new Workflow($csvReader);
            $workflow->setSkipItemOnFailure(true);

            // Create a writer: you need Doctrine’s EntityManager.
            $doctrineWriter = new DoctrineWriter($em, $map->getClass(), $map->getFindBy());
            $doctrineWriter->setTruncate(false);

            $workflow->addWriter($doctrineWriter);

            foreach($map->getConverters() as $column)
            {
                $name = ($column->hasMapper())?$column->getMapper():$column->getName();
                $workflow->addValueConverter($name, $this->get($column->getConverter()));
            }

            $workflow->addItemConverter($map->getMappings());
            $workflow->addItemConverter($map->getIgnoredMapper());

            if($map->getDuplicateFields())
                $workflow->addFilter(new Duplicate($map->getDuplicateFields()));
            else
                die("No duplicate field checker");

            // Process the workflow
            $c  = $workflow->process();
            $ex = array();

            foreach($c->getExceptions() as $x => $e)
                $ex[$x] = $e->getMessage();

            die($csvReader->count()." Source Rows<br>".$c->getTotalProcessedCount()." Rows Processed<br>".$c->getSuccessCount()." Successfully Processed <pre>".print_r($ex,true)."</pre>");
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
