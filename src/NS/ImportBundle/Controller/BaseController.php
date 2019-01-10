<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 20/06/18
 * Time: 1:45 PM
 */

namespace NS\ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Exporter\Source\DoctrineORMQuerySourceIterator;
use Exporter\Writer\CsvWriter;
use Exporter\Writer\XlsWriter;
use Exporter\Exporter;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use NS\SentinelBundle\Filter\Type as FilterType;
//\IBD\ReportFilterType as IBDReportFilterType;
//use NS\SentinelBundle\Filter\Type\Pneumonia\ReportFilterType as PneumoniaReportFilterType;
//use NS\SentinelBundle\Filter\Type\Meningitis\ReportFilterType as MeningitisReportFilterType;
//use NS\SentinelBundle\Filter\Type\RotaVirus\ReportFilterType as RotaVirusReportFilterType;

class BaseController extends Controller
{
    protected $baseField = ['region.code', 'country.code', 'site.code', 'id'];
    protected $formParams = ['validation_groups' => ['FieldPopulation'], 'include_filter' => false, 'include_paho_format_option' => true];

    protected function getMeningForm()
    {
        $meningForm = $this->createForm(FilterType\Meningitis\ReportFilterType::class, null, $this->formParams);
        return ['exportMening' => ['form' => $meningForm->createView(), 'title' => 'Meningitis Export Filters']];
    }

    protected function getPneumoniaForm()
    {
        $pneuForm = $this->createForm(FilterType\Pneumonia\ReportFilterType::class, null, $this->formParams);
        return ['exportPneu' => ['form' => $pneuForm->createView(), 'title' => 'Pneumonia Export Filters']];
    }

    protected function getRotaForm()
    {
        $rotaForm = $this->createForm(FilterType\RotaVirus\ReportFilterType::class, null, $this->formParams);
        return ['exportRota' => ['form' => $rotaForm->createView(), 'title' => 'RotaVirus Export Filters']];
    }

    protected function getIbdForm()
    {
        $ibdForm = $this->createForm(FilterType\IBD\ReportFilterType::class, null, $this->formParams);
        return ['exportIbd' => ['form' => $ibdForm->createView(), 'title' => 'IBD Export Filters']];
    }

    protected function getForms()
    {
        $results = $this->getMeningForm();
        $results += $this->getPneumoniaForm();
        $results += $this->getRotaForm();

        return array_merge($results,$this->getIbdForm());
    }

    /**
     * @param array $metas
     * @param array $fields
     */
    protected function adjustFields(array $metas, array &$fields)
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
    protected function export($format, FormInterface $form, QueryBuilder $queryBuilder, array $fields, array $formatters): Response
    {
        $aliases = $queryBuilder->getRootAliases();
        $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $queryBuilder, $aliases[0]);

        $source = new DoctrineORMQuerySourceIterator($queryBuilder->getQuery(), $fields, 'Y-m-d', $formatters);

        $filename = sprintf('export_%s.%s', date('Y_m_d_H_i_s'), $format);

        $exporter = new Exporter([new CsvWriter('php://output', ',', '"', '\\', true, true), new XlsWriter('php://output')]);
        return $exporter->getResponse($format, $filename, $source);
    }
}
