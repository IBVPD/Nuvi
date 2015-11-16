<?php

namespace NS\SentinelBundle\Report;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Query;
use Exporter\Source\SourceIteratorInterface;
use Sonata\CoreBundle\Exporter\Exporter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class AbstractReporter
 * @package NS\SentinelBundle\Services
 */
class AbstractReporter
{
    /**
     * @var type
     */
    protected $filter;
    /**
     * @var ObjectManager
     */
    protected $entityMgr;
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @param $filter
     * @param ObjectManager $entityMgr
     * @param RouterInterface $router
     */
    public function __construct($filter, ObjectManager $entityMgr, RouterInterface $router)
    {
        $this->filter = $filter;
        $this->entityMgr = $entityMgr;
        $this->router = $router;
    }

    /**
     * @param $sites
     * @param ArrayCollection $results
     * @param $resultClass
     */
    public function populateSites($sites, ArrayCollection &$results, $resultClass)
    {
        foreach ($sites as $values) {
            $resultObj = new $resultClass;
            $resultObj->setSite($values[0]->getSite());
            $resultObj->setTotalCases($values['totalCases']);

            $results->set($resultObj->getSite()->getCode(), $resultObj);
        }
    }

    /**
     *
     * @param ArrayCollection $results
     * @param array $counts
     * @param callback $function
     */
    public function processColumn(ArrayCollection &$results, $counts, $function)
    {
        foreach ($counts as $c) {
            $fpr = $results->get($c['code']);
            // this should always be true.
            if ($fpr && method_exists($fpr, $function)) {
                call_user_func(array($fpr, $function), $c['caseCount']);
            } else {
                throw new \RunTimeException(sprintf('method error %s',$function));
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
    public function processResult($columns, $repo, $alias, &$results, $form)
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
     *
     * @param SourceIteratorInterface $source
     * @param string $format
     * @return Response
     */
    public function export(SourceIteratorInterface $source, $format = 'csv')
    {
        $filename = sprintf('export_%s.%s', date('Y_m_d_H_i_s'), $format);
        $exporter = new Exporter();

        return $exporter->getResponse($format, $filename, $source);
    }


    /**
     * @param $columns
     * @param $repo
     * @param $alias
     * @param $results
     * @param $form
     */
    public function processSitePerformanceResult($columns, $repo, $alias, &$results, $form)
    {
        foreach ($columns as $func => $pf) {
            if (method_exists($repo, $func)) {
                $query = $repo->$func($alias, $results->getKeys());

                $res = $this->filter
                    ->addFilterConditions($form, $query, $alias)
                    ->getQuery()
                    ->getResult(Query::HYDRATE_SCALAR);

                $this->processSitePerformanceColumn($results, $res, $pf);
            }
        }
    }

    /**
     *
     * @param ArrayCollection $results
     * @param array $counts
     * @param callback $function
     */
    public function processSitePerformanceColumn(ArrayCollection &$results, $counts, $function)
    {
        foreach ($counts as $c) {
            $fpr = $results->get($c['code']);
            // this should always be true.
            if ($fpr && method_exists($fpr, $function)) {
                call_user_func(array($fpr, $function), $c);
            }
        }
    }
}
