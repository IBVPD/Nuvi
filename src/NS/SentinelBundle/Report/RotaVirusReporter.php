<?php

namespace NS\SentinelBundle\Report;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Report\Result\RotaVirus\GeneralStatisticResult;
use NS\SentinelBundle\Report\Result\SiteMonthResult;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use NS\SentinelBundle\Entity\RotaVirus;
use NS\SentinelBundle\Report\Result\DataLinkingResult;
use NS\SentinelBundle\Report\Result\RotaVirus\SitePerformanceResult;
use NS\SentinelBundle\Report\Result\RotaVirus\DataQualityResult;

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
        $queryBuilder = $this->entityMgr->getRepository(Site::class)->getWithCasesForDate($alias, RotaVirus::class);

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

            $repo = $this->entityMgr->getRepository(RotaVirus::class);
            $columns = [
                'getStoolCollectionDateErrorCountBySites' => 'setStoolCollectionDateErrorCount',
                'getMissingDischargeOutcomeCountBySites' => 'setMissingDischargeOutcomeCount',
                'getMissingDischargeDateCountBySites' => 'setMissingDischargeDateCount',
                'getStoolCollectedCountBySites' => 'setStoolCollectedCount',
                'getElisaDoneCountBySites' => 'setElisaDoneCount',
                'getElisaPositiveCountBySites' => 'setElisaPositiveCount',
            ];

            $this->processResult($columns, $repo, $alias, $results, $form);

            if ($form->get('export')->isClicked()) {
                $this->exporter->export('NSSentinelBundle:Report:RotaVirus/Export/data-quality.html.twig', ['sites' => $results]);
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
        $results = new ArrayCollection();
        $alias = 'i';
        $queryBuilder = $this->entityMgr->getRepository(Site::class)->getWithCasesForDate($alias, RotaVirus::class);

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

            $repo = $this->entityMgr->getRepository(RotaVirus::class);
            $columns = [
                'getConsistentReporting' => 'addConsistentReporting',
                'getZeroReporting' => ['alias' => 'i', 'method' => 'addConsistentReporting'],
            ];

            $this->processSitePerformanceResult($columns, $repo, $alias, $results, $form);

            $columns = [
                'getSpecimenCollectedWithinTwoDays' => 'setSpecimenCollection',
                'getLabConfirmedCount' => 'setLabConfirmed',
            ];

            $this->processResult($columns, $repo, $alias, $results, $form);

            if ($form->get('export')->isClicked()) {
                $this->exporter->export('NSSentinelBundle:Report:RotaVirus/Export/site-performance.html.twig', ['sites' => $results]);
            }
        }

        return ['sites' => $results, 'form' => $form->createView()];
    }

    public function getDataLinking(Request $request, FormInterface $form, $redirectRoute)
    {
        $results = new ArrayCollection();
        $alias = 'i';

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('reset')->isClicked()) {
                return new RedirectResponse($this->router->generate($redirectRoute));
            }

            $queryBuilder = $this->entityMgr->getRepository(Country::class)->getWithCasesForDate($alias, RotaVirus::class);

            $this->filter->addFilterConditions($form, $queryBuilder, 'cf');

            $countries = $queryBuilder->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getResult();

            if (empty($countries)) {
                return ['sites' => [], 'form' => $form->createView()];
            }

            $this->populateCountries($countries, $results, DataLinkingResult::class);
            $repo = $this->entityMgr->getRepository(RotaVirus::class);

            if ($form->get('export')->isClicked()) {
                $results = $repo->getFailedLink($alias, $results->getKeys())->getQuery()->getResult();
                return $this->exporter->export('NSSentinelBundle:Report:RotaVirus/Export/data-linking.html.twig', ['results' => $results]);
            }

            $columns = [
                'getLinkedCount' => 'setLinked',
                'getFailedLinkedCount' => 'setNotLinked',
                'getNoLabCount' => 'setNoLab',
            ];

            $this->processLinkingResult($columns, $repo, $alias, $results, $form);
        }

        return ['sites' => $results, 'form' => $form->createView()];
    }

    /**
     * @param Request $request
     * @param FormInterface $form
     * @param $redirectRoute
     * @return array|RedirectResponse
     */
    public function getStats(Request $request, FormInterface $form, $redirectRoute)
    {
        return $this->retrieveStats('NSSentinelBundle:RotaVirus', new GeneralStatisticResult(), $request, $form, $redirectRoute);
    }

    public function getYearMonth(Request $request, FormInterface $form, $redirectRoute)
    {
        $results = new ArrayCollection();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('reset')->isClicked()) {
                return new RedirectResponse($this->router->generate($redirectRoute));
            }

            $alias = 'i';
            $queryBuilder = $this->entityMgr->getRepository(Site::class)->getWithCasesForDate($alias, RotaVirus::class);
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
