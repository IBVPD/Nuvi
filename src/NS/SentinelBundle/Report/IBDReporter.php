<?php

namespace NS\SentinelBundle\Report;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Report\Result\AgeDistribution;
use NS\SentinelBundle\Report\Result\CulturePositive;
use NS\SentinelBundle\Report\Result\DataLinkingResult;
use NS\SentinelBundle\Report\Result\FieldPopulationResult;
use NS\SentinelBundle\Report\Result\IBD\DataCompletionResult;
use NS\SentinelBundle\Report\Result\IBD\DataQualityResult;
use NS\SentinelBundle\Report\Result\IBD\GeneralStatisticResult;
use NS\SentinelBundle\Report\Result\IBD\SitePerformanceResult;
use NS\SentinelBundle\Report\Result\NumberEnrolledResult;
use NS\SentinelBundle\Report\Result\SiteMonthResult;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class IBDReporter extends AbstractReporter
{
    /** @var string */
    protected $class;

    /** @var string */
    protected $exportBasePath;

    public function initialize(string $class, string $exportBasePath)
    {
        $this->class          = $class;
        $this->exportBasePath = $exportBasePath;
    }

    /**
     * @return RedirectResponse|array
     */
    public function numberEnrolled(Request $request, FormInterface $form, string $redirectRoute)
    {
        $alias        = 'c';
        $queryBuilder = $this->entityMgr->getRepository($this->class)->numberAndPercentEnrolledByAdmissionDiagnosis($alias);
        $export       = false;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('reset')->isClicked()) {
                return new RedirectResponse($this->router->generate($redirectRoute));
            }

            $this->filter->addFilterConditions($form, $queryBuilder, $alias);

            $export = $form->get('export')->isClicked();
        }

        $result = new NumberEnrolledResult();
        $result->load($queryBuilder->getQuery()->getResult());

        if ($export) {
            return $this->exporter->export($this->exportBasePath . '/number-enrolled.html.twig', ['results' => $result]);
        }

        return ['results' => $result, 'form' => $form->createView()];
    }

    /**
     * @return RedirectResponse|array
     */
    public function getAnnualAgeDistribution(Request $request, FormInterface $form, string $redirectRoute)
    {
        $export       = false;
        $alias        = 'i';
        $queryBuilder = $this->entityMgr->getRepository($this->class)->getAnnualAgeDistribution($alias);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('reset')->isClicked()) {
                return new RedirectResponse($this->router->generate($redirectRoute));
            }

            $this->filter->addFilterConditions($form, $queryBuilder, $alias);

            $export = $form->get('export')->isClicked();
        }

        $result  = $queryBuilder->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getResult(Query::HYDRATE_SCALAR);
        $results = new AgeDistribution($result);

        if ($export) {
            return $this->exporter->export($this->exportBasePath . '/annual-age.html.twig', ['results' => $results]);
        }

        return ['results' => $results, 'form' => $form->createView()];
    }

    /**
     * @return RedirectResponse|array
     */
    public function getFieldPopulation(Request $request, FormInterface $form, string $redirectRoute)
    {
        $results      = new ArrayCollection();
        $alias        = 'i';
        $queryBuilder = $this->entityMgr->getRepository(Site::class)->getWithCasesForDate($alias, $this->class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('reset')->isClicked()) {
                return new RedirectResponse($this->router->generate($redirectRoute));
            }

            $this->filter->addFilterConditions($form, $queryBuilder, $alias);

            $sites = $queryBuilder->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getResult();

            if (empty($sites)) {
                return ['sites' => [], 'form' => $form->createView()];
            }

            $this->populateSites($sites, $results, FieldPopulationResult::class);

            $repo    = $this->entityMgr->getRepository($this->class);
            $columns = [
                'getCsfCollectedCountBySites'   => 'setCsfCollectedCount',
                'getBloodCollectedCountBySites' => 'setBloodCollectedCount',
                'getBloodResultCountBySites'    => 'setBloodResultCount',
                'getCsfBinaxDoneCountBySites'   => 'setCsfBinaxDoneCount',
                'getCsfBinaxResultCountBySites' => 'setCsfBinaxResultCount',
                'getCsfLatDoneCountBySites'     => 'setCsfLatDoneCount',
                'getCsfLatResultCountBySites'   => 'setCsfLatResultCount',
                'getCsfPcrCountBySites'         => 'setCsfPcrRecordedCount',
                'getCsfSpnCountBySites'         => 'setCsfSpnRecordedCount',
                'getCsfHiCountBySites'          => 'setCsfHiRecordedCount',
                'getPcrPositiveCountBySites'    => 'setPcrPositiveCount'];

            $this->processResult($columns, $repo, $alias, $results, $form);

            if ($form->get('export')->isClicked()) {
                return $this->exporter->export($this->exportBasePath . '/field-population.html.twig', ['sites' => $results]);
            }
        }

        return ['sites' => $results, 'form' => $form->createView()];
    }

    /**
     * @return RedirectResponse|array
     */
    public function getCulturePositive(Request $request, FormInterface $form, string $redirectRoute)
    {
        $alias          = 'c';
        $repo           = $this->entityMgr->getRepository($this->class);
        $cultPositiveQB = $repo->getCountByCulture($alias, true, null, null);
        $cultNegativeQB = $repo->getCountByCulture($alias, false, true, null);
        $pcrPositiveQB  = $repo->getCountByCulture($alias, false, false, true);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
        $pcrPositive     = $pcrPositiveQB->groupBy('theYear')->getQuery()->getResult();
        $results         = new CulturePositive($culturePositive, $cultureNegative, $pcrPositive);

        if ($form->get('export')->isClicked()) {
            return $this->exporter->export($this->exportBasePath . '/culture-positive.html.twig', ['results' => $results]);
        }

        return ['results' => $results, 'form' => $form->createView()];
    }

    /**
     * @return array|RedirectResponse
     */
    public function getDataQuality(Request $request, FormInterface $form, string $redirectRoute)
    {
        $results      = new ArrayCollection();
        $alias        = 'i';
        $queryBuilder = $this->entityMgr->getRepository(Site::class)->getWithCasesForDate($alias, $this->class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('reset')->isClicked()) {
                return new RedirectResponse($this->router->generate($redirectRoute));
            }

            $this->filter->addFilterConditions($form, $queryBuilder, $alias);

            $sites = $queryBuilder->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getResult();

            if (empty($sites)) {
                return ['sites' => [], 'form' => $form->createView()];
            }

            $this->populateSites($sites, $results, DataQualityResult::class);

            $repo    = $this->entityMgr->getRepository($this->class);
            $columns = [
                'getMissingAdmissionDiagnosisCountBySites' => 'setMissingAdmissionDiagnosisCount',
                'getMissingDischargeOutcomeCountBySites'   => 'setMissingDischargeOutcomeCount',
                'getMissingDischargeDiagnosisCountBySites' => 'setMissingDischargeDiagnosisCount',
            ];

            $this->processResult($columns, $repo, $alias, $results, $form);

            if ($form->get('export')->isClicked()) {
                return $this->exporter->export($this->exportBasePath . '/data-quality.html.twig', ['sites' => $results], 'xls');
            }
        }

        return ['sites' => $results, 'form' => $form->createView()];
    }

    /**
     * @return array|RedirectResponse
     */
    public function getDataCompletion(Request $request, FormInterface $form, string $redirectRoute)
    {
        $results      = new ArrayCollection();
        $alias        = 'i';
        $queryBuilder = $this->entityMgr->getRepository(Site::class)->getWithCasesForDate($alias, $this->class, true);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('reset')->isClicked()) {
                return new RedirectResponse($this->router->generate($redirectRoute));
            }

            $this->filter->addFilterConditions($form, $queryBuilder, $alias);

            $sites = $queryBuilder->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getResult();

            if (empty($sites)) {
                return ['sites' => [], 'form' => $form->createView()];
            }

            $this->populateSites($sites, $results, DataCompletionResult::class);

            $repo    = $this->entityMgr->getRepository($this->class);
            $columns = [
                'getSuspectedCountBySites'               => 'setSuspected',
                'getSuspectedWithCSFCountBySites'        => 'setSuspectedCSF',
                'getProbableCountBySites'                => 'setProbable',
                'getProbableWithBloodCountBySites'       => 'setProbableWithBlood',
                'getDischargeOutcomeCountBySites'        => 'setOutcomeAtDischarge',
                'getDischargeClassificationCountBySites' => 'setClassificationAtDischarge',
            ];

            $this->processResult($columns, $repo, $alias, $results, $form, true);

            if ($form->get('export')->isClicked()) {
                return $this->exporter->export($this->exportBasePath . '/data-completion.html.twig', ['sites' => $results], 'xls');
            }
        }

        return ['sites' => $results, 'form' => $form->createView()];
    }

    /**
     * @param Request       $request
     * @param FormInterface $form
     * @param               $redirectRoute
     *
     * @return array|RedirectResponse
     */
    public function getSitePerformance(Request $request, FormInterface $form, $redirectRoute)
    {
        $results      = new ArrayCollection();
        $alias        = 'i';
        $queryBuilder = $this->entityMgr->getRepository(Site::class)->getWithCasesForDate($alias, $this->class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('reset')->isClicked()) {
                return new RedirectResponse($this->router->generate($redirectRoute));
            }

            $this->filter->addFilterConditions($form, $queryBuilder, $alias);

            $sites = $queryBuilder->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getResult();

            if (empty($sites)) {
                return ['sites' => [], 'form' => $form->createView()];
            }

            $this->populateSites($sites, $results, SitePerformanceResult::class);

            $repo    = $this->entityMgr->getRepository($this->class);
            $columns = [
                'getConsistentReporting' => 'addConsistentReporting',
                'getZeroReporting'       => ['method' => 'addConsistentReporting', 'alias' => 'i'],
            ];

            $this->processSitePerformanceResult($columns, $repo, $alias, $results, $form);

            $columns = [
                'getNumberOfSpecimenCollectedCount' => 'setSpecimenCollection',
                'getNumberOfConfirmedCount'         => 'setConfirmed',
                'getNumberOfLabConfirmedCount'      => 'setLabConfirmed',
            ];

            $this->processResult($columns, $repo, $alias, $results, $form);
        }

        return ['sites' => $results, 'form' => $form->createView()];
    }

    /**
     * @param Request       $request
     * @param FormInterface $form
     * @param               $redirectRoute
     *
     * @return array|RedirectResponse
     */
    public function getDataLinking(Request $request, FormInterface $form, $redirectRoute)
    {
        $results = new ArrayCollection();
        $alias   = 'i';

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('reset')->isClicked()) {
                return new RedirectResponse($this->router->generate($redirectRoute));
            }

            $queryBuilder = $this->entityMgr->getRepository(Country::class)->getWithCasesForDate($alias, $this->class);

            $this->filter->addFilterConditions($form, $queryBuilder, $alias);

            $countries = $queryBuilder->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getResult();

            if (empty($countries)) {
                return ['sites' => [], 'form' => $form->createView()];
            }

            $this->populateCountries($countries, $results, DataLinkingResult::class);
            $repo = $this->entityMgr->getRepository($this->class);

            if ($form->get('export')->isClicked()) {
                $results = $repo->getFailedLink($alias, $results->getKeys())->getQuery()->getResult();
                return $this->exporter->export($this->exportBasePath . '/data-linking.html.twig', ['results' => $results]);
            }

            $columns = [
                'getLinkedCount'       => 'setLinked',
                'getFailedLinkedCount' => 'setNotLinked',
                'getNoLabCount'        => 'setNoLab',
            ];

            $this->processLinkingResult($columns, $repo, $alias, $results, $form);
        }

        return ['sites' => $results, 'form' => $form->createView()];
    }

    /**
     * @param Request       $request
     * @param FormInterface $form
     * @param               $redirectRoute
     *
     * @return array|RedirectResponse
     */
    public function getStats(Request $request, FormInterface $form, $redirectRoute)
    {
        return $this->retrieveStats($this->class, new GeneralStatisticResult(), $request, $form, $redirectRoute);
    }

    public function getYearMonth(Request $request, FormInterface $form, $redirectRoute)
    {
        $results = new ArrayCollection();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('reset')->isClicked()) {
                return new RedirectResponse($this->router->generate($redirectRoute));
            }

            $alias        = 'i';
            $queryBuilder = $this->entityMgr->getRepository(Site::class)->getWithCasesForDate($alias, $this->class);
            $queryBuilder->addSelect('MONTH(i.adm_date) as admMonth');

            $this->filter->addFilterConditions($form, $queryBuilder, $alias);

            $sites = $queryBuilder->addGroupBy('admMonth')->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getResult();

            if (empty($sites)) {
                return ['sites' => [], 'form' => $form->createView()];
            }

            $this->populateYearMonth($sites, $results, SiteMonthResult::class);

            if ($form->get('export')->isClicked()) {
                return $this->exporter->export('NSSentinelBundle:Report:Export/year-month.html.twig', ['sites' => $results]);
            }
        }

        return ['sites' => $results, 'form' => $form->createView()];
    }
}
