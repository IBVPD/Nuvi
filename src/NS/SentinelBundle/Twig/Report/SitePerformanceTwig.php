<?php

namespace NS\SentinelBundle\Twig\Report;

use NS\SentinelBundle\Report\Result\AbstractSitePerformanceResult;
use Twig\TwigFunction;
use Twig_Environment;
use Twig_Extension;
use Twig_SimpleFunction;

class SitePerformanceTwig extends Twig_Extension
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * SitePerformanceTwig constructor.
     * @param Twig_Environment $environment
     */
    public function __construct(Twig_Environment $environment)
    {
        $this->twig = $environment;
    }


    /**
     * @inheritDoc
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('consistentReporting', [$this, 'renderConsistentReporting'], ['is_safe'=> ['html']]),
            new TwigFunction('minimumCases', [$this, 'renderMinimumCases'], ['is_safe'=> ['html']]),
            new TwigFunction('specimenCollected', [$this, 'renderSpecimenCollected'], ['is_safe'=> ['html']]),
            new TwigFunction('specimenCollectedCount', [$this, 'renderSpecimenCollectedCount'], ['is_safe'=> ['html']]),
            new TwigFunction('labConfirmed', [$this, 'renderLabConfirmed'], ['is_safe'=> ['html']]),
        ];
    }

    /**
     * @param AbstractSitePerformanceResult $result
     * @return string
     */
    public function renderMinimumCases(AbstractSitePerformanceResult $result)
    {
        return $this->twig->render('NSSentinelBundle:Report:flag.html.twig', [
            'flag'=>$result->getMinimumNumberOfCases(),
            'string'=>$result->getMinimumNumberOfCasesString(),
            'result'=>$result->getTotalCases(),
        ]);
    }

    /**
     * @param AbstractSitePerformanceResult $result
     * @return string
     */
    public function renderConsistentReporting(AbstractSitePerformanceResult $result)
    {
        return $this->twig->render('NSSentinelBundle:Report:flag.html.twig', [
            'flag'=>$result->getConsistentReporting(),
            'string'=>$result->getConsistentReportingString(),
            'result'=>$result->getConsistentReportingCount(),
        ]);
    }

    /**
     * @param AbstractSitePerformanceResult $result
     * @return string
     */
    public function renderSpecimenCollected(AbstractSitePerformanceResult $result)
    {
        $params = [
            'result' => sprintf('%d %%',(int)$result->getSpecimenCollectionPercent()),
            'value'  => $result->getSpecimenCollection(),
            'flag'   => $result->hasMinimumSpecimenCollected(),
            'string' => $result->getMinimumSpecimenCollectedString(),
        ];

        return $this->twig->render('NSSentinelBundle:Report:flag.html.twig',$params);
    }

    /**
     * @param AbstractSitePerformanceResult $result
     * @return string
     */
    public function renderSpecimenCollectedCount(AbstractSitePerformanceResult $result)
    {
        $params = [
            'result' => sprintf('%d',(int)$result->getSpecimenCollection()),
            'value'  => $result->getSpecimenCollection(),
            'flag'   => $result->hasMinimumSpecimenCollected(),
            'string' => $result->getMinimumSpecimenCollectedString(),
        ];

        return $this->twig->render('NSSentinelBundle:Report:flag.html.twig',$params);
    }

    /**
     * @param AbstractSitePerformanceResult $result
     * @return string
     */
    public function renderLabConfirmed(AbstractSitePerformanceResult $result)
    {
        $params = [
            'result' => sprintf('%d %%',(int)$result->getLabConfirmedPercent()),
            'value'  => $result->getLabConfirmed(),
            'flag'   => $result->hasMinimumLabConfirmed(),
            'string' => $result->getMinimumLabConfirmedString(),
        ];

        return $this->twig->render('NSSentinelBundle:Report:flag.html.twig',$params);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'SitePerformanceTwigExtension';
    }
}
