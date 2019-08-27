<?php

namespace NS\ImportBundle\Controller;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Exporter\Exporter;
use Exporter\Source\DoctrineORMQuerySourceIterator;
use Exporter\Writer\CsvWriter;
use Exporter\Writer\XlsWriter;
use JMS\Serializer\Annotation\AccessorOrder;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\SentinelBundle\Filter\Type as FilterType;
use ReflectionClass;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller implements TranslationContainerInterface
{
    protected $class;
    protected $baseField = ['region.code', 'country.code', 'site.code', 'site.name', 'id'];
    protected $formParams = ['validation_groups' => ['FieldPopulation'], 'include_filter' => false, 'include_paho_format_option' => true];

    protected function getMeningForm(): array
    {
        $meningForm = $this->createForm(FilterType\Meningitis\ReportFilterType::class, null, $this->formParams);
        return ['exportMening' => ['form' => $meningForm->createView(), 'title' => 'Meningitis Export Filters']];
    }

    protected function getPneumoniaForm(): array
    {
        $pneuForm = $this->createForm(FilterType\Pneumonia\ReportFilterType::class, null, $this->formParams);
        return ['exportPneu' => ['form' => $pneuForm->createView(), 'title' => 'Pneumonia Export Filters']];
    }

    protected function getRotaForm(): array
    {
        $rotaForm = $this->createForm(FilterType\RotaVirus\ReportFilterType::class, null, $this->formParams);
        return ['exportRota' => ['form' => $rotaForm->createView(), 'title' => 'Rotavirus Export Filters']];
    }

    protected function getIbdForm(): array
    {
        $ibdForm = $this->createForm(FilterType\IBD\ReportFilterType::class, null, $this->formParams);
        return ['exportIbd' => ['form' => $ibdForm->createView(), 'title' => 'IBD Export Filters']];
    }

    protected function getForms()
    {
        $results = $this->getMeningForm();
        $results += $this->getPneumoniaForm();
        $results += $this->getRotaForm();

        return array_merge($results, $this->getIbdForm());
    }

    protected function adjustFields(array $metas, array $fields): array
    {
        /** @var ClassMetadata $meta */
        foreach ($metas as $sprint => $meta) {
            foreach ($meta->getFieldNames() as $field) {
                if ($field === 'id') {
                    continue;
                }

                $fields[] = sprintf($sprint, $field);
            }
        }

        return $this->sortFields($fields);
    }

    protected function sortFields(array $fields): array
    {
        if ($this->class === null) {
            return $fields;
        }

        $reader = $this->get('annotation_reader');
        try {
            $reflectedClass = new ReflectionClass($this->class);
        } catch (ReflectionException $e) {
            return $fields;
        }

        /** @var AccessorOrder|null $annotation */
        $annotation = $reader->getClassAnnotation($reflectedClass, AccessorOrder::class);
        if ($annotation) {
            $order     = $annotation->custom;
            $newFields = [];
            foreach ($order as $key => $field) {
                if (in_array($field, $fields, true)) {
                    $newFields[] = $field;
                }
            }

            $newFields += array_diff($newFields, $fields);

            return $newFields;
        }

        return $fields;
    }

    /**
     * @param string        $format
     * @param FormInterface $form
     * @param QueryBuilder  $queryBuilder
     * @param array         $fields
     * @param array         $formatters
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

    public static function getTranslationMessages(): array
    {
        return [
            new Message('Rotavirus Export Filters'),
            new Message('Pneumonia Export Filters'),
            new Message('Meningitis Export Filters'),
            new Message('IBD Export Filters'),
        ];
    }
}
