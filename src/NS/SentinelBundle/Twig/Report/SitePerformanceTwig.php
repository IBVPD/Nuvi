<?php

namespace NS\SentinelBundle\Twig\Report;

use NS\SentinelBundle\Report\Result\AbstractSitePerformanceResult;

class SitePerformanceTwig extends \Twig_Extension
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * SitePerformanceTwig constructor.
     * @param \Twig_Environment $environment
     */
    public function __construct(\Twig_Environment $environment)
    {
        $this->twig = $environment;
    }


    /**
     * @inheritDoc
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('consistentReporting', array($this, 'renderConsistentReporting'), array('is_safe'=>array('html'))),
            new \Twig_SimpleFunction('minimumCases', array($this, 'renderMinimumCases'), array('is_safe'=>array('html'))),
            new \Twig_SimpleFunction('specimenCollected', array($this, 'renderSpecimenCollected'), array('is_safe'=>array('html'))),
            new \Twig_SimpleFunction('labConfirmed', array($this, 'renderLabConfirmed'), array('is_safe'=>array('html'))),
            );
    }

    /**
     * @param AbstractSitePerformanceResult $result
     * @return string
     */
    public function renderMinimumCases(AbstractSitePerformanceResult $result)
    {
        return $this->twig->render('NSSentinelBundle:Report:flag.html.twig',array(
            'flag'=>$result->getMinimumNumberOfCases(),
            'string'=>$result->getMinimumNumberOfCasesString(),
            'result'=>$result->getMinimumNumberOfCases(),
            ));
    }

    /**
     * @param AbstractSitePerformanceResult $result
     * @return string
     */
    public function renderConsistentReporting(AbstractSitePerformanceResult $result)
    {
        return $this->twig->render('NSSentinelBundle:Report:flag.html.twig',array(
            'flag'=>$result->getConsistentReporting(),
            'string'=>$result->getConsistentReportingString(),
            'result'=>$result->getConsistentReportingCount(),
            ));
    }

    /**
     * @param AbstractSitePerformanceResult $result
     * @return string
     */
    public function renderSpecimenCollected(AbstractSitePerformanceResult $result)
    {
        $params = array(
            'result' => sprintf('%d %%',(int)$result->getSpecimenCollectionPercent()),
            'value'  => $result->getSpecimenCollection(),
            'flag'   => $result->hasMinimumSpecimenCollected(),
            'string' => $result->getMinimumSpecimenCollectedString(),
        );

        return $this->twig->render('NSSentinelBundle:Report:flag.html.twig',$params);
    }

    /**
     * @param AbstractSitePerformanceResult $result
     * @return string
     */
    public function renderLabConfirmed(AbstractSitePerformanceResult $result)
    {
        $params = array(
            'result' => sprintf('%d %%',(int)$result->getLabConfirmedPercent()),
            'value'  => $result->getLabConfirmed(),
            'flag'   => $result->hasMinimumLabConfirmed(),
            'string' => $result->getMinimumLabConfirmedString(),
        );

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
