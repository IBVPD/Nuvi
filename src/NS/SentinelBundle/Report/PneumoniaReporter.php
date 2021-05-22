<?php

namespace NS\SentinelBundle\Report;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Report\Result\Pneumonia\DataCompletionResult;
use NS\SentinelBundle\Report\Result\Pneumonia\SitePerformanceResult;
use RuntimeException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class PneumoniaReporter extends IBDReporter
{
    public function getFieldPopulation(Request $request, FormInterface $form, $redirectRoute)
    {
        throw new RuntimeException("This report doesn't make sense for pneumonia");
    }

    /**
     * @param Request       $request
     * @param FormInterface $form
     * @param               $redirectRoute
     *
     * @return array|RedirectResponse
     */
    public function getDataCompletion(Request $request, FormInterface $form, $redirectRoute)
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

}
