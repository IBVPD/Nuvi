<?php

namespace NS\ImportBundle\Controller;

use NS\ImportBundle\Formatter\DateTimeFormatter;
use NS\SentinelBundle\Entity\Pneumonia\NationalLab;
use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
use NS\SentinelBundle\Entity\Pneumonia\ReferenceLab;
use NS\SentinelBundle\Entity\Pneumonia\SiteLab;
use NS\SentinelBundle\Filter\Type\Pneumonia\ReportFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/{_locale}/export")
 */
class PneumoniaController extends BaseController
{
    protected $class = Pneumonia::class;

    /**
     * @Route("/pneumonia",name="exportPneu")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function exportAction(Request $request): Response
    {
        $form = $this->createForm(ReportFilterType::class, null, $this->formParams);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $modelManager = $this->get('doctrine.orm.entity_manager');
            $fields       = $this->baseField;
            $areas        = $form['areas']->getData();
            if (!empty($areas)) {
                $meta    = [];
                $classes = ['%s' => Pneumonia::class,
                    'siteLab.%s' => SiteLab::class,
                    'referenceLab.%s' => ReferenceLab::class,
                    'nationalLab.%s' => NationalLab::class,
                ];

                foreach ($areas as $area) {
                    $meta[$area] = $modelManager->getClassMetadata($classes[$area]);
                }
            } else {
                $meta = [
                    '%s' => $modelManager->getClassMetadata(Pneumonia::class),
                    'siteLab.%s' => $modelManager->getClassMetadata(SiteLab::class),
                    'referenceLab.%s' => $modelManager->getClassMetadata(ReferenceLab::class),
                    'nationalLab.%s' => $modelManager->getClassMetadata(NationalLab::class),
                ];
            }

            $fields                = $this->adjustFields($meta, $fields);
            $query                 = $modelManager->getRepository(Pneumonia::class)->exportQuery('i');
            $arrayChoiceFormatter  = $this->get('ns_import.array_choice_formatter');
            $spnTypeGroupFormatter = $this->get('ns_import.serotype_group_formatter');

            if ($form->get('pahoFormat')->getData()) {
                $arrayChoiceFormatter->usePahoFormat();
            }

            $format = $form->get('exportFormat')->getData() ? 'xls' : 'csv';

            return $this->export($format, $form, $query, $fields, [$spnTypeGroupFormatter, $arrayChoiceFormatter, new DateTimeFormatter()]);
        }

        $forms                       = $this->getForms();
        $forms['exportPneu']['form'] = $form->createView();
        return $this->render('NSImportBundle:Export:index.html.twig', ['forms' => $forms]);
    }
}
