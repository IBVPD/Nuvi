<?php

namespace NS\ImportBundle\Controller;

use \Doctrine\ORM\QueryBuilder;
use \Exporter\Source\DoctrineORMQuerySourceIterator;
use \NS\SentinelBundle\Entity\IBD;
use \NS\SentinelBundle\Entity\RotaVirus;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

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
        $alias     = 'i';
        $params    = array('validation_groups' => array('FieldPopulation'), 'include_filter' => false);
        $baseField = array('id', 'site.name', 'country.name', 'region.name');


        $ibdForm = $this->createForm('IBDReportFilterType', null, $params);
        $ibdForm->handleRequest($request);
        if ($ibdForm->isValid())
        {
            $obj          = new IBD();
            $modelManager = $this->get('doctrine.orm.entity_manager');
            $fields       = array_merge($baseField, $obj->getMinimumRequiredFields());

            $metas = array(
                "siteLab.%s"      => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\IBD\SiteLab'),
                "referenceLab.%s" => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\IBD\ReferenceLab'),
                "nationalLab.%s"  => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\IBD\NationalLab'),
            );

            $this->adjustFields($metas, $fields);

            $query = $modelManager->getRepository('NSSentinelBundle:IBD')->exportQuery($alias);

            return $this->export('xls', $ibdForm, $query, $fields);
        }

        $rotaForm = $this->createForm('RotaVirusReportFilterType', null, $params);
        $rotaForm->handleRequest($request);
        if ($rotaForm->isValid())
        {
            $obj          = new RotaVirus();
            $modelManager = $this->get('doctrine.orm.entity_manager');
            $fields       = array_merge($baseField, $obj->getMinimumRequiredFields());
            $metas        = array(
                "siteLab.%s"      => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\Rota\SiteLab'),
                "referenceLab.%s" => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\Rota\ReferenceLab'),
                "nationalLab.%s"  => $modelManager->getClassMetadata('NS\SentinelBundle\Entity\Rota\NationalLab'),
            );

            $this->adjustFields($metas, $fields);
            $query = $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:RotaVirus')->exportQuery($alias);

            return $this->export('xls', $rotaForm, $query, $fields);
        }

        return array('ibdForm' => $ibdForm->createView(), 'rotaForm' => $rotaForm->createView());
    }

    /**
     *
     * @param array $metas
     * @param array $fields
     */
    private function adjustFields(array $metas, array &$fields)
    {
        foreach ($metas as $sprint => $meta)
        {
            foreach ($meta->getFieldNames() as $field)
            {
                if ($field == 'id')
                    continue;

                $fields[] = sprintf($sprint, $field);
            }
        }
    }

    /**
     *
     * @param string $format
     * @param FormInterface $form
     * @param QueryBuilder $queryBuilder
     * @param array $fields
     * @return Response
     */
    public function export($format, FormInterface $form, QueryBuilder $queryBuilder, array $fields)
    {
        $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $queryBuilder, $queryBuilder->getRootAlias());

        $query    = $queryBuilder->getQuery();
        $source   = new DoctrineORMQuerySourceIterator($query, $fields);
        $filename = sprintf('export_%s.%s', date('Y_m_d_H_i_s'), $format);

        return $this->get('sonata.admin.exporter')->getResponse($format, $filename, $source);
    }
}