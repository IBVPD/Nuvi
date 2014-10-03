<?php

namespace NS\SentinelBundle\Services;

use \Doctrine\Common\Collections\ArrayCollection;
use \Doctrine\Common\Persistence\ObjectManager;
use \Doctrine\ORM\Query;
use \Exporter\Source\ArraySourceIterator;
use \NS\SentinelBundle\Exporter\DoctrineCollectionSourceIterator;
use \NS\SentinelBundle\Result\AgeDistribution;
use \NS\SentinelBundle\Result\CulturePositive;
use \NS\SentinelBundle\Result\FieldPopulationResult;
use \NS\SentinelBundle\Result\NumberEnrolledResult;
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
    private $entityMgr;
    private $router;

    public function __construct(Exporter $exporter, $filter, ObjectManager $entityMgr, RouterInterface $router)
    {
        $this->exporter  = $exporter;
        $this->filter    = $filter;
        $this->entityMgr = $entityMgr;
        $this->router    = $router;
    }

    public function numberEnrolled(Request $request, FormInterface $form, $redirectRoute)
    {
        $alias        = 'c';
        $queryBuilder = $this->entityMgr->getRepository('NSSentinelBundle:IBD')->numberAndPercentEnrolledByAdmissionDiagnosis($alias);
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
        $qb     = $this->entityMgr->getRepository('NSSentinelBundle:IBD')->getAnnualAgeDistribution($alias);

        $form->handleRequest($request);
        if($form->isValid())
        {
            if($form->get('reset')->isClicked())
                return new RedirectResponse($this->router->generate($redirectRoute));
            else
                $this->filter->addFilterConditions($form, $qb, $alias);

            $export = ($form->get('export')->isClicked());
        }

        $r       = $qb->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getResult(Query::HYDRATE_SCALAR);
        $results = new AgeDistribution($r);

        if($export)
            return $this->export(new ArraySourceIterator($results->toArray()),'xls');

        return array('results'=>$results,'form'=>$form->createView());
    }

    private function _populateSites($sites,&$results)
    {
        foreach($sites as $values)
        {
            $fpr = new FieldPopulationResult();
            $fpr->setSite($values[0]->getSite());
            $fpr->setTotalCases($values['totalCases']);

            $results->set($fpr->getSite()->getCode(),$fpr);
        }
    }

    public function getFieldPopulation(Request $request, FormInterface $form, $redirectRoute)
    {
        $results = new ArrayCollection();
        $alias   = 'i';
        $qb      = $this->entityMgr->getRepository('NSSentinelBundle:Site')->getWithCasesForDate($alias);

        $form->handleRequest($request);
        if($form->isValid())
        {
            if($form->get('reset')->isClicked())
                return new RedirectResponse($this->router->generate($redirectRoute));
            
            $this->filter->addFilterConditions($form, $qb, $alias);

            $sites = $qb->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getResult();

            if(empty($sites))
                return array('sites'=>array(),'form'=> $form->createView());

            $this->_populateSites($sites, $results);

            $ibdRepo = $this->entityMgr->getRepository('NSSentinelBundle:IBD');
            $columns = array('getCsfCollectedCountBySites','getBloodCollectedCountBySites',
                            'getBloodResultCountBySites','getCsfBinaxDoneCountBySites',
                            'getCsfBinaxResultCountBySites','getCsfLatDoneCountBySites',
                            'getCsfLatResultCountBySites','getCsfPcrCountBySites','getCsfSpnCountBySites',
                            'getCsfHiCountBySites','getPcrPositiveCountBySites');

            foreach($columns as $f)
            {
                $r = $this->filter->addFilterConditions($form, $ibdRepo->$f($alias, $results->getKeys()), $alias)->getQuery()->getResult(Query::HYDRATE_SCALAR);
                $this->processColumn($results,$r);
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

    private function processColumn($results, $counts)
    {
        foreach($counts as $c)
        {
            $fpr = $results->get($c['code']);
            if($fpr) // this should always be true.
                $fpr->setCsfCollectedCount($c['caseCount']);
        }

    }
    public function getCulturePositive(Request $request, FormInterface $form, $redirectRoute)
    {
        $alias          = 'c';
        $repo           = $this->entityMgr->getRepository('NSSentinelBundle:IBD');
        $cultPositiveQB = $repo->getCountByCulture($alias, true, null, null);
        $cultNegativeQB = $repo->getCountByCulture($alias, false, true, null);
        $pcrPositiveQB  = $repo->getCountByCulture($alias, false, false, true);

        $form->handleRequest($request);

        if($form->isValid())
        {
            if($form->get('reset')->isClicked())
                return new RedirectResponse($this->router->generate($redirectRoute));
            else
            {
                $this->filter->addFilterConditions($form, $cultPositiveQB, $alias);
                $this->filter->addFilterConditions($form, $cultNegativeQB, $alias);
                $this->filter->addFilterConditions($form, $pcrPositiveQB, $alias);
            }
        }

        $cp = $cultPositiveQB->groupBy('theYear')->getQuery()->getResult();
        $cn = $cultNegativeQB->groupBy('theYear')->getQuery()->getResult();
        $pp = $pcrPositiveQB->groupBy('theYear')->getQuery()->getResult();

        $ro = new CulturePositive($cp,$cn,$pp);

        if($form->get('export')->isClicked())
            return $this->export(new ArraySourceIterator($ro->toArray()));

        return array('results' => $ro, 'form' => $form->createView());
    }

    public function export($source, $format='csv')
    {
        $filename = sprintf('export_%s.%s',date('Y_m_d_H_i_s'), $format);

        return $this->exporter->getResponse($format, $filename, $source);
    }
}
