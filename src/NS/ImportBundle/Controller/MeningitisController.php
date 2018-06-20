<?php

namespace NS\ImportBundle\Controller;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Exporter\Source\DoctrineORMQuerySourceIterator;
use NS\ImportBundle\Formatter\DateTimeFormatter;
use Exporter\Writer\CsvWriter;
use Exporter\Writer\XlsWriter;
use NS\SentinelBundle\Entity\Meningitis\Meningitis;
use NS\SentinelBundle\Entity\Meningitis\NationalLab;
use NS\SentinelBundle\Entity\Meningitis\ReferenceLab;
use NS\SentinelBundle\Entity\Meningitis\SiteLab;
use NS\SentinelBundle\Filter\Type\Meningitis\ReportFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Exporter\Exporter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of ExportController
 *
 * @author gnat
 * @Route("/{_locale}/export")
 */
class MeningitisController extends BaseController
{

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/meningitis",name="exportMening")
     */
    public function exportAction(Request $request)
    {
        $form = $this->createForm(ReportFilterType::class, null, $this->formParams);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $modelManager = $this->get('doctrine.orm.entity_manager');
            $fields = $this->baseField;
            $meta = [
                '%s' => $modelManager->getClassMetadata(Meningitis::class),
                'siteLab.%s' => $modelManager->getClassMetadata(SiteLab::class),
                'referenceLab.%s' => $modelManager->getClassMetadata(ReferenceLab::class),
                'nationalLab.%s' => $modelManager->getClassMetadata(NationalLab::class),
            ];

            $this->adjustFields($meta, $fields);

            $query = $modelManager->getRepository('NSSentinelBundle:Meningitis\Meningitis')->exportQuery('i');
            $arrayChoiceFormatter = $this->get('ns_import.array_choice_formatter');

            if ($form->get('pahoFormat')->getData()) {
                $arrayChoiceFormatter->usePahoFormat();
            }

            $format = $form->get('exportFormat')->getData() ? 'xls' : 'csv';

            return $this->export($format, $form, $query, $fields, [$arrayChoiceFormatter, new DateTimeFormatter()]);
        }

        $forms = $this->getForms();
        $forms['exportMening']['form'] = $form->createView();
        return $this->render('NSImportBundle:Export:index.html.twig', ['forms' => $forms]);
    }
}
