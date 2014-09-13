<?php

namespace NS\SentinelBundle\Services;

use \DateTime;
use \Doctrine\Common\Collections\ArrayCollection;
use \Doctrine\Common\Persistence\ObjectManager;
use \Doctrine\ORM\Query;
use \Exporter\Source\ArraySourceIterator;
use \NS\SentinelBundle\Result\FieldPopulationResult;
use \NS\SentinelBundle\Result\NumberEnrolledResult;
use \NS\SentinelBundle\Exporter\DoctrineCollectionSourceIterator;
use \Sonata\CoreBundle\Exporter\Exporter;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\Routing\RouterInterface;

/**
 * Description of Report
 *
 * @author gnat
 */
class Report
{
    private $exporter;
    private $filter;
    private $em;
    private $router;

    public function __construct(Exporter $exporter, $filter, ObjectManager $em, RouterInterface $router)
    {
        $this->exporter = $exporter;
        $this->filter   = $filter;
        $this->em       = $em;
        $this->router   = $router;
    }

    public function numberEnrolled(Request $request, FormInterface $form, $redirectRoute)
    {
        $alias        = 'c';
        $queryBuilder = $this->em->getRepository('NSSentinelBundle:IBD')->numberAndPercentEnrolledByAdmissionDiagnosis($alias);
        $export       = false;

        $form->handleRequest($request);

        if($form->isValid())
        {
            if($form->get('reset')->isClicked())
                return new RedirectResponse($this->router->generate($redirectRoute));
            else
                $this->filter->addFilterConditions($form, $queryBuilder, $alias);

            $export = ($form->get('export')->isClicked());
        }

        $result = new NumberEnrolledResult();
        $result->load($queryBuilder->getQuery()->getResult());

        if($export)
            return $this->export(new ArraySourceIterator($result->all()),'csv');

        return array('results' => $result, 'form' => $form->createView());
    }

    public function getAnnualAgeDistribution(Request $request,  FormInterface $form, $redirectRoute)
    {
        $export = false;
        $alias  = 'i';
        $qb     = $this->em->getRepository('NSSentinelBundle:IBD')->getAnnualAgeDistribution($alias);

        $form->handleRequest($request);
        if($form->isValid())
        {
            if($form->get('reset')->isClicked())
                return new RedirectResponse($this->router->generate($redirectRoute));
            else
                $this->filter->addFilterConditions($form, $qb, $alias);

            $export = ($form->get('export')->isClicked());
        }

        $r       = $qb->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getResult();
        $results = array();

        foreach($r as $case)
        {
            if(!isset($results[$case['theYear']]))
                $results[$case['theYear']] = array('year'=>$case['theYear'],0=>0,1=>0,2=>0,3=>0,4=>0, -1=>0);

            $results[$case['theYear']][$case[0]->getAgeDistribution()]++;
            $results[$case['theYear']][0]++;
        }

        if($export)
            return $this->export(new ArraySourceIterator($results),'xls');

        return array('results'=>$results,'form'=>$form->createView());
    }

    public function getFieldPopulation(Request $request, FormInterface $form, $redirectRoute)
    {
        $results = new ArrayCollection();
        $alias   = 'i';
        $qb      = $this->em->getRepository('NSSentinelBundle:Site')->getWithCasesForDate($alias);

        $form->handleRequest($request);
        if($form->isValid())
        {
            if($form->get('reset')->isClicked())
                return new RedirectResponse($this->router->generate($redirectRoute));
            
            $this->filter->addFilterConditions($form, $qb, $alias);

            $sites = $qb->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getResult();

            if(empty($sites))
                return array('sites'=>array(),'form'=> $form->createView());

            foreach($sites as $x => $values)
            {
                $fpr = new FieldPopulationResult();
                $fpr->setSite($values[0]->getSite());
                $fpr->setTotalCases($values['totalCases']);

                $results->set($fpr->getSite()->getCode(),$fpr);
            }

            $ibdRepo      = $this->em->getRepository('NSSentinelBundle:IBD');

            $csfCollected = $this->filter->addFilterConditions($form, $ibdRepo->getCsfCollectedCountBySites($alias, $results->getKeys()), $alias)->getQuery()->getResult(Query::HYDRATE_SCALAR);
            foreach($csfCollected as $c)
            {
                $fpr = $results->get($c['code']);
                if($fpr) // this should always be true.
                    $fpr->setCsfCollectedCount($c['csfCollectedCount']);
            }

            $bloodCollected = $this->filter->addFilterConditions($form, $ibdRepo->getBloodCollectedCountBySites($alias, $results->getKeys()),$alias)->getQuery()->getResult(Query::HYDRATE_SCALAR);
            foreach($bloodCollected as $c)
            {
                $fpr = $results->get($c['code']);
                if($fpr) // this should always be true.
                    $fpr->setBloodCollectedCount($c['bloodCollectedCount']);
            }

            $bloodResultCount = $this->filter->addFilterConditions($form, $ibdRepo->getBloodResultCountBySites($alias, $results->getKeys()),$alias)->getQuery()->getResult(Query::HYDRATE_SCALAR);
            foreach($bloodResultCount as $c)
            {
                $fpr = $results->get($c['code']);
                if($fpr) // this should always be true.
                    $fpr->setBloodResultCount($c['bloodResultCount']);
            }

            $csfBinaxDoneCount = $this->filter->addFilterConditions($form, $ibdRepo->getCsfBinaxDoneCountBySites($alias, $results->getKeys()),$alias)->getQuery()->getResult(Query::HYDRATE_SCALAR);
            foreach($csfBinaxDoneCount as $c)
            {
                $fpr = $results->get($c['code']);
                if($fpr) // this should always be true.
                    $fpr->setCsfBinaxDoneCount($c['csfBinaxDone']);
            }

            $csfBinaxResultCount = $this->filter->addFilterConditions($form, $ibdRepo->getCsfBinaxResultCountBySites($alias, $results->getKeys()),$alias)->getQuery()->getResult(Query::HYDRATE_SCALAR);
            foreach($csfBinaxResultCount as $c)
            {
                $fpr = $results->get($c['code']);
                if($fpr) // this should always be true.
                    $fpr->setCsfBinaxResultCount($c['csfBinaxResult']);
            }

            $csfLatDoneCount = $this->filter->addFilterConditions($form, $ibdRepo->getCsfLatDoneCountBySites($alias, $results->getKeys()),$alias)->getQuery()->getResult(Query::HYDRATE_SCALAR);
            foreach($csfLatDoneCount as $c)
            {
                $fpr = $results->get($c['code']);
                if($fpr) // this should always be true.
                    $fpr->setCsfLatDoneCount($c['csfLatDone']);
            }

            $csfLatResultCount = $this->filter->addFilterConditions($form, $ibdRepo->getCsfLatResultCountBySites($alias, $results->getKeys()),$alias)->getQuery()->getResult(Query::HYDRATE_SCALAR);
            foreach($csfLatResultCount as $c)
            {
                $fpr = $results->get($c['code']);
                if($fpr) // this should always be true.
                    $fpr->setCsfLatResultCount($c['csfLatResult']);
            }

            $csfPcrRecordedCount = $this->filter->addFilterConditions($form, $ibdRepo->getCsfPcrCountBySites($alias, $results->getKeys()),$alias)->getQuery()->getResult(Query::HYDRATE_SCALAR);
            foreach($csfPcrRecordedCount as $c)
            {
                $fpr = $results->get($c['code']);
                if($fpr) // this should always be true.
                    $fpr->setCsfPcrRecordedCount($c['csfPcrResultCount']);
            }

            $csfSpnRecordedCount = $this->filter->addFilterConditions($form, $ibdRepo->getCsfSpnCountBySites($alias, $results->getKeys()),$alias)->getQuery()->getResult(Query::HYDRATE_SCALAR);
            foreach($csfSpnRecordedCount as $c)
            {
                $fpr = $results->get($c['code']);
                if($fpr) // this should always be true.
                    $fpr->setCsfSpnRecordedCount($c['csfSpnResultCount']);
            }

            $csfHiRecordedCount = $this->filter->addFilterConditions($form, $ibdRepo->getCsfHiCountBySites($alias, $results->getKeys()),$alias)->getQuery()->getResult(Query::HYDRATE_SCALAR);
            foreach($csfHiRecordedCount as $c)
            {
                $fpr = $results->get($c['code']);
                if($fpr) // this should always be true.
                    $fpr->setCsfHiRecordedCount($c['csfHiResultCount']);
            }

            $pcrPositiveCount = $this->filter->addFilterConditions($form, $ibdRepo->getPcrPositiveCountBySites($alias, $results->getKeys()),$alias)->getQuery()->getResult(Query::HYDRATE_SCALAR);
            foreach($pcrPositiveCount as $c)
            {
                $fpr = $results->get($c['code']);
                if($fpr) // this should always be true.
                    $fpr->setPcrPositiveCount($c['pcrPositiveCount']);
            }

            if($form->get('export')->isClicked())
            {
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

                return $this->export(new DoctrineCollectionSourceIterator($results,$fields));
            }
        }

        return array('sites' => $results, 'form' => $form->createView());
    }

    public function export($source, $format='csv')
    {
        $filename = sprintf('export_%s.%s',date('Y_m_d_H_i_s'), $format);

        return $this->exporter->getResponse($format, $filename, $source);
    }
}
