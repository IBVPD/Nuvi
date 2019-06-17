<?php

namespace NS\SentinelBundle\Report;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Query;
use Exception;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Report\Export\Exporter;
use NS\SentinelBundle\Report\Result\AbstractGeneralStatisticResult;
use RuntimeException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class AbstractReporter
{
    /** @var FilterBuilderUpdaterInterface */
    protected $filter;

    /** @var ObjectManager */
    protected $entityMgr;

    /** @var RouterInterface */
    protected $router;

    /** @var Exporter $exporter */
    protected $exporter;

    public function __construct(FilterBuilderUpdaterInterface $filter, ObjectManager $entityMgr, RouterInterface $router, Exporter $exporter)
    {
        $this->filter = $filter;
        $this->entityMgr = $entityMgr;
        $this->router = $router;
        $this->exporter = $exporter;
    }

    /**
     * @param $sites
     * @param ArrayCollection $results
     * @param $resultClass
     */
    public function populateSites($sites, ArrayCollection $results, $resultClass): void
    {
        foreach ($sites as $values) {
            $resultObj = new $resultClass($values[0]->getSite());
            $resultObj->setTotalCases($values['totalCases']);

            $results->set($resultObj->getSite()->getCode(), $resultObj);
        }
    }

    public function processColumn(ArrayCollection $results, array $counts, string $function): void
    {
        foreach ($counts as $c) {
            $fpr = $results->get($c['code']);
            // this should always be true.
            if ($fpr && method_exists($fpr, $function)) {
                call_user_func([$fpr, $function], $c['caseCount']);
            } else {
                throw new RuntimeException(sprintf('method error %s', $function));
            }
        }
    }

    /**
     * @param $columns
     * @param $repo
     * @param $alias
     * @param $results
     * @param $form
     */
    public function processResult($columns, $repo, $alias, &$results, $form): void
    {
        foreach ($columns as $func => $pf) {
            if (method_exists($repo, $func)) {
                $query = $repo->$func($alias, $results->getKeys());

                $res = $this->filter
                    ->addFilterConditions($form, $query, $alias)
                    ->getQuery()
                    ->getResult(Query::HYDRATE_SCALAR);

                $this->processColumn($results, $res, $pf);
            }
        }
    }

    /**
     * @param $columns
     * @param $repo
     * @param $alias
     * @param $results
     * @param $form
     */
    public function processSitePerformanceResult($columns, $repo, $alias, &$results, $form): void
    {
        foreach ($columns as $func => $pf) {
            if (method_exists($repo, $func)) {
                $query = $repo->$func($alias, $results->getKeys());

                $res = $this->filter
                    ->addFilterConditions($form, $query, is_array($pf) ? $pf['alias']: $alias)
                    ->getQuery()
                    ->getResult(Query::HYDRATE_SCALAR);

                $this->processSitePerformanceColumn($results, $res, is_array($pf) ? $pf['method'] : $pf);
            }
        }
    }

    public function processSitePerformanceColumn(ArrayCollection $results, array $counts, string $function): void
    {
        foreach ($counts as $c) {
            $fpr = $results->get($c['code']);
            // this should always be true.
            if ($fpr && method_exists($fpr, $function)) {
                call_user_func([$fpr, $function], $c);
            }
        }
    }

    /**
     * @param $countries
     * @param ArrayCollection $results
     * @param $resultClass
     */
    public function populateCountries($countries, ArrayCollection $results, $resultClass): void
    {
        foreach ($countries as $values) {
            $resultObj = new $resultClass;
            $resultObj->setCountry($values[0]->getCountry());
            $resultObj->setTotalCases($values['totalCases']);

            $results->set($resultObj->getCountry()->getCode(), $resultObj);
        }
    }

    /**
     * @param $columns
     * @param $repo
     * @param $alias
     * @param $results
     * @param $form
     */
    public function processLinkingResult($columns, $repo, $alias, &$results, $form): void
    {
        foreach ($columns as $func => $pf) {
            if (method_exists($repo, $func)) {
                $query = $repo->$func($alias, $results->getKeys());

                try {
                    $res = $this->filter
                        ->addFilterConditions($form, $query, 'cf')
                        ->getQuery()
                        ->getResult(Query::HYDRATE_SCALAR);
                } catch (Exception $exception) {
                    throw new RuntimeException('SQL Exception with func: '.$func, null, $exception);
                }

                $this->processColumn($results, $res, $pf);
            }
        }
    }

    /**
     * @param string $repoClass
     * @param AbstractGeneralStatisticResult $result
     * @param Request $request
     * @param FormInterface $form
     * @param $redirectRoute
     * @return array|RedirectResponse
     */
    public function retrieveStats($repoClass, AbstractGeneralStatisticResult $result, Request $request, FormInterface $form, $redirectRoute)
    {
        $alias = 'i';

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('reset')->isClicked()) {
                return new RedirectResponse($this->router->generate($redirectRoute));
            }

            $repo = $this->entityMgr->getRepository($repoClass);
            $columns = [
                'getGenderDistribution' => 'setGenderDistribution',
                'getAgeInMonthDistribution' => 'setAgeInMonthDistribution',
                'getLocationDistribution' => 'setLocationDistribution',
                'getDischargeOutcomeDistribution' => 'setDischargeOutcomeDistribution',
                'getMonthlyDistribution' => 'setMonthlyDistribution'
            ];

            foreach ($columns as $repoFunction => $resultFunction) {
                $query = $repo->$repoFunction($alias);
                $this->filter->addFilterConditions($form, $query, $alias);
                $results = $query->getQuery()->getScalarResult();
                $result->$resultFunction($results);
            }

            return ['form' => $form->createView(), 'result' => $result];
        }

        return ['form' => $form->createView(), 'result' => $result];
    }

    protected function populateYearMonth($sites, ArrayCollection $results, $resultClass): void
    {
        $siteCodes = [];
        foreach ($sites as $values) {
            /** @var Site $site */
            $site = $values[0]->getSite();

            if(!isset($siteCodes[$site->getCode()])) {
                $siteCodes[$site->getCode()] = new $resultClass($site);
                $results->set($site->getCode(), $siteCodes[$site->getCode()]);
            }

            $siteCodes[$site->getCode()]->addMonth($values['admMonth'], $values['totalCases']);
        }
    }
}
