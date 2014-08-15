<?php

namespace NS\ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Reader\CsvReader;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use Ddeboer\DataImport\ValueConverter\DateTimeValueConverter;

/**
 * Description of ImportController
 *
 * @author gnat
 * @Route("/import")
 */
class ImportController extends Controller
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
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

            $output = array();
            $arrayWriter = new \Ddeboer\DataImport\Writer\ArrayWriter($output);
            // Create a writer: you need Doctrine’s EntityManager.
//            $doctrineWriter = new DoctrineWriter($em, $map->getClass(), $map->getFindBy());
//            $doctrineWriter->setTruncate(false);

            $workflow->addWriter($arrayWriter);
//            $workflow->addWriter($doctrineWriter);

            foreach($map->getConverters() as $column)
            {
                $name = ($column->hasMapper())?$column->getMapper():$column->getName();
                $workflow->addValueConverter($name, $this->get($column->getConverter()));
            }

            $workflow->addItemConverter($map->getMappings());
            $workflow->addItemConverter($map->getIgnoredMapper());

            // Process the workflow
            $c = $workflow->process();
            die($c->getTotalProcessedCount()." Rows Processed <pre>".print_r(array_keys($output[0]),true)."</pre> <pre>".print_r($output[0]['lab'],true)."</pre>");
        }

        return array('form'=>$form->createView());
    }
}
