<?php

namespace NS\SentinelBundle\Report;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use Exporter\Source\ArraySourceIterator;
use NS\SentinelBundle\Exporter\DoctrineCollectionSourceIterator;
use NS\SentinelBundle\Report\Result\AgeDistribution;
use NS\SentinelBundle\Report\Result\CulturePositive;
use NS\SentinelBundle\Report\Result\DataQualityResult;
use NS\SentinelBundle\Report\Result\NumberEnrolledResult;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of Report
 *
 * @author gnat
 */
class IBDReporter extends AbstractReporter
{
    /**
     *
     * @param Request $request
     * @param FormInterface $form
     * @param string $redirectRoute
     * @return RedirectResponse|array
     */
    public function numberEnrolled(Request $request, FormInterface $form, $redirectRoute)
    {
        $alias = 'c';
        $queryBuilder = $this->entityMgr->getRepository('NSSentinelBundle:IBD')->numberAndPercentEnrolledByAdmissionDiagnosis($alias);
        $export = false;

        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($form->get('reset')->isClicked()) {
                return new RedirectResponse($this->router->generate($redirectRoute));
            } else {
                $this->filter->addFilterConditions($form, $queryBuilder, $alias);
            }

            $export = ($form->get('export')->isClicked());
        }

        $result = new NumberEnrolledResult();
        $result->load($queryBuilder->getQuery()->getResult());

        if ($export) {
            return $this->export(new ArraySourceIterator($result->all()), 'csv');
        }

        return array('results' => $result, 'form' => $form->createView());
    }

    /**
     *
     * @param Request $request
     * @param FormInterface $form
     * @param string $redirectRoute
     * @return RedirectResponse|array
     */
    public function getAnnualAgeDistribution(Request $request, FormInterface $form, $redirectRoute)
    {
        $export = false;
        $alias = 'i';
        $queryBuilder = $this->entityMgr->getRepository('NSSentinelBundle:IBD')->getAnnualAgeDistribution($alias);

        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('reset')->isClicked()) {
                return new RedirectResponse($this->router->generate($redirectRoute));
            } else {
                $this->filter->addFilterConditions($form, $queryBuilder, $alias);
            }

            $export = ($form->get('export')->isClicked());
        }

        $result = $queryBuilder->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getResult(Query::HYDRATE_SCALAR);
        $results = new AgeDistribution($result);

        if ($export) {
            return $this->export(new ArraySourceIterator($results->toArray()), 'xls');
        }

        return array('results' => $results, 'form' => $form->createView());
    }

    /**
     *
     * @param Request $request
     * @param FormInterface $form
     * @param string $redirectRoute
     * @return RedirectResponse|array
     */
    public function getFieldPopulation(Request $request, FormInterface $form, $redirectRoute)
    {
        $results = new ArrayCollection();
        $alias = 'i';
        $queryBuilder = $this->entityMgr->getRepository('NSSentinelBundle:Site')->getWithCasesForDate($alias, 'NS\SentinelBundle\Entity\IBD');

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

            $this->populateSites($sites, $results, 'NS\SentinelBundle\Report\Result\FieldPopulationResult');

            $repo = $this->entityMgr->getRepository('NSSentinelBundle:IBD');
            $columns = array(
                'getCsfCollectedCountBySites' => 'setCsfCollectedCount',
                'getBloodCollectedCountBySites' => 'setBloodCollectedCount',
                'getBloodResultCountBySites' => 'setBloodResultCount',
                'getCsfBinaxDoneCountBySites' => 'setCsfBinaxDoneCount',
                'getCsfBinaxResultCountBySites' => 'setCsfBinaxResultCount',
                'getCsfLatDoneCountBySites' => 'setCsfLatDoneCount',
                'getCsfLatResultCountBySites' => 'setCsfLatResultCount',
                'getCsfPcrCountBySites' => 'setCsfPcrRecordedCount',
                'getCsfSpnCountBySites' => 'setCsfSpnRecordedCount',
                'getCsfHiCountBySites' => 'setCsfHiRecordedCount',
                'getPcrPositiveCountBySites' => 'setPcrPositiveCount');

            $this->processResult($columns, $repo, $alias, $results, $form);

            if ($form->get('export')->isClicked()) {
                $fields = array(
                    'site.country.region',
                    'site.country',
                    'site',
                    'site.ibdIntenseSupport',
                    'totalCases',
                    'csfCollectedCount',
                    'csfCollectedPercent',
                    'csfResultCount',
                    'csfResultPercent',
                    'bloodCollectedCount',
                    'bloodCollectedPercent',
                    'bloodResultCount',
                    'bloodResultPercent',
                    'bloodEqual',
                    'csfBinaxResultPercent',
                    'csfLatResultPercent',
                    'pcrPositiveCount',
                    'csfPcrRecordedCount',
                    'csfPcrRecordedPercent',
                    'csfSpnRecordedCount',
                    'csfSpnRecordedPercent',
                    'csfHiRecordedCount',
                    'csfHiRecordedPercent',
                );

                return $this->export(new DoctrineCollectionSourceIterator($results, $fields));
            }
        }

        return array('sites' => $results, 'form' => $form->createView());
    }

    /**
     *
     * @param Request $request
     * @param FormInterface $form
     * @param string $redirectRoute
     * @return RedirectResponse|array
     */
    public function getCulturePositive(Request $request, FormInterface $form, $redirectRoute)
    {
        $alias = 'c';
        $repo = $this->entityMgr->getRepository('NSSentinelBundle:IBD');
        $cultPositiveQB = $repo->getCountByCulture($alias, true, null, null);
        $cultNegativeQB = $repo->getCountByCulture($alias, false, true, null);
        $pcrPositiveQB = $repo->getCountByCulture($alias, false, false, true);

        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($form->get('reset')->isClicked()) {
                return new RedirectResponse($this->router->generate($redirectRoute));
            } else {
                $this->filter->addFilterConditions($form, $cultPositiveQB, $alias);
                $this->filter->addFilterConditions($form, $cultNegativeQB, $alias);
                $this->filter->addFilterConditions($form, $pcrPositiveQB, $alias);
            }
        }

        $culturePositive = $cultPositiveQB->groupBy('theYear')->getQuery()->getResult();
        $cultureNegative = $cultNegativeQB->groupBy('theYear')->getQuery()->getResult();
        $pcrPositive = $pcrPositiveQB->groupBy('theYear')->getQuery()->getResult();
        $results = new CulturePositive($culturePositive, $cultureNegative, $pcrPositive);

        if ($form->get('export')->isClicked()) {
            return $this->export(new ArraySourceIterator($results->toArray()));
        }

        return array('results' => $results, 'form' => $form->createView());
    }

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
        $queryBuilder = $this->entityMgr->getRepository('NSSentinelBundle:Site')->getWithCasesForDate($alias, 'NS\SentinelBundle\Entity\IBD');

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

            $this->populateSites($sites, $results, 'NS\SentinelBundle\Report\Result\IBD\DataQualityResult');

            $repo = $this->entityMgr->getRepository('NSSentinelBundle:IBD');
            $columns = array(
                'getMissingAdmissionDiagnosisCountBySites' => 'setMissingAdmissionDiagnosisCount',
                'getMissingDischargeOutcomeCountBySites' => 'setMissingDischargeOutcomeCount',
                'getMissingDischargeDiagnosisCountBySites' => 'setMissingDischargeDiagnosisCount',
                );

            $this->processResult($columns, $repo, $alias, $results, $form);

            if ($form->get('export')->isClicked()) {
                $fields = array(
                    'site.country.region.code',
                    'site.country.code',
                    'site.code',
                    'totalCases',
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

    /**
     * @param Request $request
     * @param FormInterface $form
     * @param $redirectRoute
     * @return array|RedirectResponse
     */
    public function getSitePerformance(Request $request, FormInterface $form, $redirectRoute)
    {
        $results = new ArrayCollection();
        $alias = 'i';
        $queryBuilder = $this->entityMgr->getRepository('NSSentinelBundle:Site')->getWithCasesForDate($alias, 'NS\SentinelBundle\Entity\IBD');

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

            $this->populateSites($sites, $results, 'NS\SentinelBundle\Report\Result\IBD\SitePerformanceResult');

            $repo = $this->entityMgr->getRepository('NSSentinelBundle:IBD');
            $columns = array(
                'getConsistentReporting' => 'addConsistentReporting',
            );

            $this->processSitePerformanceResult($columns, $repo, $alias, $results, $form);

            $columns = array(
                'getNumberOfSpecimenCollectedCount' => 'setSpecimenCollection',
            );

            $this->processResult($columns, $repo, $alias, $results, $form);
        }

        return array('sites' => $results, 'form' => $form->createView());
    }

    /**
     * @param Request $request
     * @param FormInterface $form
     * @param $redirectRoute
     * @return array|RedirectResponse
     */
    public function getDataLinking(Request $request, FormInterface $form, $redirectRoute)
    {
        $results = new ArrayCollection();
        $alias = 'i';
        $queryBuilder = $this->entityMgr->getRepository('NSSentinelBundle:Country')->getWithCasesForDate($alias, 'NS\SentinelBundle\Entity\IBD');

        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('reset')->isClicked()) {
                return new RedirectResponse($this->router->generate($redirectRoute));
            }

            $this->filter->addFilterConditions($form, $queryBuilder, $alias);

            $countries = $queryBuilder->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getResult();

            if (empty($countries)) {
                return array('sites' => array(), 'form' => $form->createView());
            }

            $this->populateCountries($countries, $results, 'NS\SentinelBundle\Report\Result\DataLinkingResult');

            $repo = $this->entityMgr->getRepository('NSSentinelBundle:IBD');
            $columns = array(
                'getLinkedCount' => 'setLinked',
                'getFailedLinkedCount' => 'setNotLinked',
                'getNoLabCount' => 'setNoLab',
            );

            $this->processLinkingResult($columns, $repo, $alias, $results, $form);
        }

        return array('sites' => $results, 'form' => $form->createView());
    }
}
