<?php

namespace NS\SentinelBundle\Report;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use NS\SentinelBundle\Exporter\DoctrineCollectionSourceIterator;
use NS\SentinelBundle\Report\Result\DataQualityResult;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class RotaVirusReporter extends AbstractReporter
{
    /**
     * @param Request $request
     * @param FormInterface $form
     * @param $redirectRoute
     * @return array|RedirectResponse
     */
    public function getDataQuality(Request $request, FormInterface $form, $redirectRoute)
    {
        $results = new ArrayCollection();
        $alias = 'i';
        $queryBuilder = $this->entityMgr->getRepository('NSSentinelBundle:Site')->getWithCasesForDate($alias,'NS\SentinelBundle\Entity\RotaVirus');

        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('reset')->isClicked()) {
                return new RedirectResponse($this->router->generate($redirectRoute));
            }

            $this->filter->addFilterConditions($form, $queryBuilder, $alias);

            $sites = $queryBuilder->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getResult();

            if (empty($sites)) {
                return array('sites' => array(), 'form' => $form->createView());
            }

            $this->populateSites($sites,$results,'NS\SentinelBundle\Report\Result\RotaVirus\DataQualityResult');

            $repo = $this->entityMgr->getRepository('NSSentinelBundle:RotaVirus');
            $columns = array(
                'getBirthdateErrorCountBySites'=>'setBirthdayErrorCount',
                'getStoolCollectionDateErrorCountBySites' => 'setStoolCollectionDateErrorCount',
                'getMissingDischargeOutcomeCountBySites' => 'setMissingDischargeOutcomeCount',
                'getMissingDischargeDateCountBySites' => 'setMissingDischargeDateCount',
            );

            $this->processResult($columns, $repo, $alias, $results, $form);

            if ($form->get('export')->isClicked()) {
                $fields = array(
                    'site.region.code',
                    'site.country.code',
                    'site.code',
                    'totalCases',
                    'dateOfBirthErrorCount',
                    'dateOfBirthErrorPercent',
                    'missingAdmissionDiagnosisCount',
                    'missingAdmissionDiagnosisPercent',
                    'missingDischargeOutcomeCount',
                    'missingDischargeOutcomePercent',
                    'missingDischargeDiagnosisCount',
                    'missingDischargeDiagnosisPercent'
                );

                return $this->export(new DoctrineCollectionSourceIterator($results, $fields));
            }
        }

        return array('sites' => $results, 'form' => $form->createView());
    }
}