<?php

namespace NS\SentinelBundle\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Query;
use Exporter\Source\ArraySourceIterator;
use Exporter\Source\SourceIteratorInterface;
use NS\SentinelBundle\Exporter\DoctrineCollectionSourceIterator;
use NS\SentinelBundle\Result\AgeDistribution;
use NS\SentinelBundle\Result\CulturePositive;
use NS\SentinelBundle\Result\DataQualityResult;
use NS\SentinelBundle\Result\FieldPopulationResult;
use NS\SentinelBundle\Result\NumberEnrolledResult;
use Sonata\CoreBundle\Exporter\Exporter;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * Description of Report
 *
 * @author gnat
 */
class Report
{
    private $exporter;
    private $filter;
    private $entityMgr;
    private $router;

    /**
     *
     * @param Exporter $exporter
     * @param type $filter
     * @param ObjectManager $entityMgr
     * @param RouterInterface $router
     */
    public function __construct(Exporter $exporter, $filter, ObjectManager $entityMgr, RouterInterface $router)
    {
        $this->exporter = $exporter;
        $this->filter = $filter;
        $this->entityMgr = $entityMgr;
        $this->router = $router;
    }

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
     * @param array $sites
     * @param ArrayCollection $results
     */
    private function populateSites($sites, ArrayCollection &$results)
    {
        foreach ($sites as $values) {
            $fpr = new FieldPopulationResult();
            $fpr->setSite($values[0]->getSite());
            $fpr->setTotalCases($values['totalCases']);

            $results->set($fpr->getSite()->getCode(), $fpr);
        }
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
        $queryBuilder = $this->entityMgr->getRepository('NSSentinelBundle:Site')->getWithCasesForDate($alias);

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

            $this->populateSites($sites, $results);

            $ibdRepo = $this->entityMgr->getRepository('NSSentinelBundle:IBD');
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

            foreach ($columns as $f => $pf) {
                if (method_exists($ibdRepo, $f)) {
                    $query = $ibdRepo->$f($alias, $results->getKeys());

                    $res = $this->filter
                        ->addFilterConditions($form, $query, $alias)
                        ->getQuery()
                        ->getResult(Query::HYDRATE_SCALAR);

                    $this->processColumn($results, $res, $pf);
                }
            }

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
     * @param ArrayCollection $results
     * @param array $counts
     * @param callback $function
     */
    private function processColumn(ArrayCollection $results, $counts, $function)
    {
        foreach ($counts as $c) {
            $fpr = $results->get($c['code']);
            // this should always be true.
            if ($fpr && method_exists($fpr, $function)) {
                call_user_func(array($fpr, $function), $c['caseCount']);
            }
        }
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
        $queryBuilder = $this->entityMgr->getRepository('NSSentinelBundle:Site')->getWithCasesForDate($alias);

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

            foreach ($sites as $values) {
                $fpr = new DataQualityResult();
                $fpr->setSite($values[0]->getSite());
                $fpr->setTotalCases($values['totalCases']);

                $results->set($fpr->getSite()->getCode(), $fpr);
            }

            $ibdRepo = $this->entityMgr->getRepository('NSSentinelBundle:IBD');
            $columns = array(
                'getBirthdateErrorCountBySites'=>'setBirthdayErrorCount',
                'getMissingAdmissionDiagnosisCountBySites' => 'setMissingAdmissionDiagnosisCount',
                'getMissingDischargeOutcomeCountBySites' => 'setMissingDischargeOutcomeCount',
                'getMissingDischargeDiagnosisCountBySites' => 'setMissingDischargeDiagnosisCount',
                );

            foreach ($columns as $func => $pf) {
                if (method_exists($ibdRepo, $func)) {
                    $query = $ibdRepo->$func($alias, $results->getKeys());

                    $res = $this->filter
                        ->addFilterConditions($form, $query, $alias)
                        ->getQuery()
                        ->getResult(Query::HYDRATE_SCALAR);

                    $this->processColumn($results, $res, $pf);
                }
            }

            if ($form->get('export')->isClicked()) {
                $fields = array(
                    'site.country.region.code',
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

    /**
     *
     * @param SourceIteratorInterface $source
     * @param string $format
     * @return Response
     */
    public function export(SourceIteratorInterface $source, $format = 'csv')
    {
        $filename = sprintf('export_%s.%s', date('Y_m_d_H_i_s'), $format);

        return $this->exporter->getResponse($format, $filename, $source);
    }
}
