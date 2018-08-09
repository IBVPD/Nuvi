<?php

namespace NS\ImportBundle\Controller;

use NS\ImportBundle\Formatter\DateTimeFormatter;
use NS\SentinelBundle\Entity\RotaVirus;
use NS\SentinelBundle\Filter\Type\RotaVirus\ReportFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of ExportController
 *
 * @author gnat
 * @Route("/{_locale}/export")
 */
class RotaVirusController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/rota",name="exportRota")
     */
    public function exportAction(Request $request)
    {
        $form = $this->createForm(ReportFilterType::class, null, $this->formParams);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $modelManager = $this->get('doctrine.orm.entity_manager');
            $fields = $this->baseField;
            $meta = [
                '%s' => $modelManager->getClassMetadata(RotaVirus::class),
                'siteLab.%s' => $modelManager->getClassMetadata(RotaVirus\SiteLab::class),
                'referenceLab.%s' => $modelManager->getClassMetadata(RotaVirus\ReferenceLab::class),
                'nationalLab.%s' => $modelManager->getClassMetadata(RotaVirus\NationalLab::class),
            ];

            $this->adjustFields($meta, $fields);
            $query = $modelManager->getRepository('NSSentinelBundle:RotaVirus')->exportQuery('i');
            $arrayChoiceFormatter = $this->get('ns_import.array_choice_formatter');

            if ($form->get('pahoFormat')->getData()) {
                $arrayChoiceFormatter->usePahoFormat();
            }

            $format = $form->get('exportFormat')->getData() ? 'xls' : 'csv';

            return $this->export($format, $form, $query, $fields, [$arrayChoiceFormatter, new DateTimeFormatter()]);
        }

        $forms = $this->getForms();
        $forms['exportRota']['form'] = $form->createView();
        return $this->render('NSImportBundle:Export:index.html.twig', ['forms' => $forms]);
    }
}
