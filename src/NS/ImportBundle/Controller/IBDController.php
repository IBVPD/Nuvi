<?php

namespace NS\ImportBundle\Controller;

use NS\ImportBundle\Formatter\DateTimeFormatter;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Filter\Type\IBD\ReportFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of ExportController
 *
 * @author gnat
 * @Route("/{_locale}/export")
 */
class IBDController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/ibd",name="exportIbd")
     */
    public function exportAction(Request $request)
    {
        $form = $this->createForm(ReportFilterType::class, null, $this->formParams);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $modelManager = $this->get('doctrine.orm.entity_manager');
            $fields = $this->baseField;
            $meta = [
                '%s' => $modelManager->getClassMetadata(IBD::class),
                'siteLab.%s' => $modelManager->getClassMetadata(IBD\SiteLab::class),
                'referenceLab.%s' => $modelManager->getClassMetadata(IBD\ReferenceLab::class),
                'nationalLab.%s' => $modelManager->getClassMetadata(IBD\NationalLab::class),
            ];

            $this->adjustFields($meta, $fields);

            $query = $modelManager->getRepository('NSSentinelBundle:IBD')->exportQuery('i');
            $arrayChoiceFormatter = $this->get('ns_import.array_choice_formatter');

            if ($form->get('pahoFormat')->getData()) {
                $arrayChoiceFormatter->usePahoFormat();
            }

            $format = $form->get('exportFormat')->getData() ? 'xls' : 'csv';

            return $this->export($format, $form, $query, $fields, [$arrayChoiceFormatter, new DateTimeFormatter()]);
        }

        $forms = $this->getForms();
        $forms['exportIbd']['form'] = $form->createView();

        return $this->render('NSImportBundle:Export:index.html.twig', ['forms' => $forms]);
    }
}
