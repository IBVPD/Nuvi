<?php

namespace NS\ImportBundle\Controller;

use Ddeboer\DataImport\Reader\CsvReader;
use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Writer\ArrayWriter;
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
            $em   = $this->get('doctrine.orm.entity_manager');
            $map  = $form['map']->getData();
            $f    = $form['file']->getData();
            $file = $f->openFile();
            $log  = $this->get('logger');
            // Create and configure the reader
            $csvReader = new CsvReader($file,',');

            // Tell the reader that the first row in the CSV file contains column headers
            $csvReader->setHeaderRowNumber(0);
//            $csvReader->setColumnHeaders($map->getColumnHeaders());

            // Create the workflow from the reader
            $workflow = new Workflow($csvReader,$log);

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

            // Process the workflow
            $workflow->process();
            $results = $doctrineWriter->getResults();

            $format   = 'csv';
            $source   = new ArraySourceIterator($results,array('id','caseId','site','country', 'region'));
            $filename = sprintf('export_%s.%s',date('Y_m_d_H_i_s'), $format);

            return $this->get('sonata.admin.exporter')->getResponse($format, $filename, $source);
//            $exporter = $this->get('');
//            die($c->getTotalProcessedCount()." Rows Processed <pre>".print_r(array_keys($output[0]),true)."</pre>");
        }

        return array('form'=>$form->createView());
    }
}
