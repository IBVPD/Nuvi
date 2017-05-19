<?php

namespace NS\ImportBundle\Controller;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Exporter\Source\DoctrineORMQuerySourceIterator;
use NS\ImportBundle\Formatter\DateTimeFormatter;
use Exporter\Writer\CsvWriter;
use Exporter\Writer\XlsWriter;
use NS\SentinelBundle\Filter\Type\IBD\ReportFilterType as IBDReportFilterType;
use NS\SentinelBundle\Filter\Type\RotaVirus\ReportFilterType as RotaVirusReportFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
class ExportController extends Controller
{
    private $baseField = ['region.code','country.code','site.code', 'id'];
    private $formParams = ['validation_groups' => ['FieldPopulation'], 'include_filter' => false, 'include_paho_format_option' => true];

    /**
     * @Route("/",name="exportIndex")
     * @Method(methods={"GET","POST"})
     */
    public function indexAction()
    {
        $ibdForm = $this->createForm(IBDReportFilterType::class, null, $this->formParams);
        $rotaForm = $this->createForm(RotaVirusReportFilterType::class, null, $this->formParams);

        return $this->render('NSImportBundle:Export:index.html.twig', ['ibdForm' => $ibdForm->createView(), 'rotaForm' => $rotaForm->createView()]);
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
        if ($ibdForm->isSubmitted() && $ibdForm->isValid()) {
            $modelManager = $this->get('doctrine.orm.entity_manager');
            $fields = $this->baseField;
            $meta = [
                '%s' => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\IBD'),
                'siteLab.%s' => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\IBD\SiteLab'),
                'referenceLab.%s' => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\IBD\ReferenceLab'),
                'nationalLab.%s' => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\IBD\NationalLab'),
            ];

            $this->adjustFields($meta, $fields);

            $query = $modelManager->getRepository('NSSentinelBundle:IBD')->exportQuery('i');
            $arrayChoiceFormatter = $this->get('ns_import.array_choice_formatter');

            if ($ibdForm->get('pahoFormat')->getData()) {
                $arrayChoiceFormatter->usePahoFormat();
            }

            $format = $ibdForm->get('exportFormat')->getData() ? 'xls':'csv';

            return $this->export($format, $ibdForm, $query, $fields, [$arrayChoiceFormatter, new DateTimeFormatter()]);
        }

        $rotaForm = $this->createForm(RotaVirusReportFilterType::class, null, $this->formParams);

        return $this->render('NSImportBundle:Export:index.html.twig', ['ibdForm' => $ibdForm->createView(), 'rotaForm' => $rotaForm->createView()]);

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
        if ($rotaForm->isSubmitted() && $rotaForm->isValid()) {
            $modelManager = $this->get('doctrine.orm.entity_manager');
            $fields = $this->baseField;
            $meta = [
                '%s' => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\RotaVirus'),
                'siteLab.%s' => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\RotaVirus\SiteLab'),
                'referenceLab.%s' => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\RotaVirus\ReferenceLab'),
                'nationalLab.%s' => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\RotaVirus\NationalLab'),
            ];

            $this->adjustFields($meta, $fields);
            $query = $modelManager->getRepository('NSSentinelBundle:RotaVirus')->exportQuery('i');
            $arrayChoiceFormatter = $this->get('ns_import.array_choice_formatter');

            if ($rotaForm->get('pahoFormat')->getData()) {
                $arrayChoiceFormatter->usePahoFormat();
            }

            $format = $rotaForm->get('exportFormat')->getData() ? 'xls':'csv';

            return $this->export($format, $rotaForm, $query, $fields, [$arrayChoiceFormatter, new DateTimeFormatter()]);
        }

        $ibdForm = $this->createForm(IBDReportFilterType::class, null, $this->formParams);

        return $this->render('NSImportBundle:Export:index.html.twig', ['ibdForm' => $ibdForm->createView(), 'rotaForm' => $rotaForm->createView()]);
    }

    /**
     * @param array $metas
     * @param array $fields
     */
    private function adjustFields(array $metas, array &$fields)
    {
        /** @var ClassMetadata $meta */
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
     * @param string $format
     * @param FormInterface $form
     * @param QueryBuilder $queryBuilder
     * @param array $fields
     * @param array $formatters
     *
     * @return Response
     */
    private function export($format, FormInterface $form, QueryBuilder $queryBuilder, array $fields, array $formatters)
    {
        $aliases = $queryBuilder->getRootAliases();
        $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $queryBuilder, $aliases[0]);

        $source = new DoctrineORMQuerySourceIterator($queryBuilder->getQuery(), $fields, 'Y-m-d', $formatters);

        $filename = sprintf('export_%s.%s', date('Y_m_d_H_i_s'), $format);

        $exporter = new Exporter([new CsvWriter('php://output',',','"','\\',true,true), new XlsWriter('php://output')]);
        return $exporter->getResponse($format, $filename, $source);
    }
}
