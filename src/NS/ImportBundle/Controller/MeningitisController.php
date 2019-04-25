<?php

namespace NS\ImportBundle\Controller;

use NS\ImportBundle\Formatter\DateTimeFormatter;
use NS\SentinelBundle\Entity\Meningitis\Meningitis;
use NS\SentinelBundle\Entity\Meningitis\NationalLab;
use NS\SentinelBundle\Entity\Meningitis\ReferenceLab;
use NS\SentinelBundle\Entity\Meningitis\SiteLab;
use NS\SentinelBundle\Filter\Type\Meningitis\ReportFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/{_locale}/export")
 */
class MeningitisController extends BaseController
{
    protected $class = Meningitis::class;

    /**
     * @Route("/meningitis",name="exportMening")
     *
     * @param Request $request
     * @return Response
     */
    public function exportAction(Request $request): Response
    {
        $form = $this->createForm(ReportFilterType::class, null, $this->formParams);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $modelManager = $this->get('doctrine.orm.entity_manager');
            $fields       = $this->baseField;
            $meta         = [
                '%s' => $modelManager->getClassMetadata(Meningitis::class),
                'siteLab.%s' => $modelManager->getClassMetadata(SiteLab::class),
                'referenceLab.%s' => $modelManager->getClassMetadata(ReferenceLab::class),
                'nationalLab.%s' => $modelManager->getClassMetadata(NationalLab::class),
            ];

            $fields                = $this->adjustFields($meta, $fields);
            $query                 = $modelManager->getRepository(Meningitis::class)->exportQuery('i');
            $arrayChoiceFormatter  = $this->get('ns_import.array_choice_formatter');
            $spnTypeGroupFormatter = $this->get('ns_import.serotype_group_formatter');

            if ($form->get('pahoFormat')->getData()) {
                $arrayChoiceFormatter->usePahoFormat();
            }

            $format = $form->get('exportFormat')->getData() ? 'xls' : 'csv';

            return $this->export($format, $form, $query, $fields, [$spnTypeGroupFormatter, $arrayChoiceFormatter, new DateTimeFormatter()]);
        }

        $forms                         = $this->getForms();
        $forms['exportMening']['form'] = $form->createView();
        return $this->render('NSImportBundle:Export:index.html.twig', ['forms' => $forms]);
    }
}
