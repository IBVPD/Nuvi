<?php

namespace NS\SentinelBundle\Report;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Report\Result\Meningitis\SitePerformanceResult;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class MeningitisReport extends IBDReporter
{
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
