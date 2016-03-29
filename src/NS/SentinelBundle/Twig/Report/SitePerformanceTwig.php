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
        return $this->twig->render('NSSentinelBundle:Report:flag.html.twig', array('result'=>$result->getMinimumNumberOfCases()));
    }

    /**
     * @param AbstractSitePerformanceResult $result
     * @return string
     */
    public function renderConsistentReporting(AbstractSitePerformanceResult $result)
    {
        return $this->twig->render('NSSentinelBundle:Report:flag.html.twig', array('result'=>$result->getConsistentReporting()));
    }

    /**
     * @param AbstractSitePerformanceResult $result
     * @return string
     */
    public function renderSpecimenCollected(AbstractSitePerformanceResult $result)
    {
        $classification = $result->hasMinimumSpecimenCollected();

        $color = $this->getClassificationColor($classification);

        $params = array(
            'includeInfobox' => true,
            'percent' => (int)$result->getSpecimenCollectionPercent(),
            'value'=>$result->getSpecimenCollection(),
            'color' => $color,
            'classification'=>$classification,
            'classificationString'=>$result->getMinimumSpecimenCollectedString(),
        );
        return $this->twig->render('NSSentinelBundle:Report:infobox.html.twig', $params);
    }

    /**
     * @param AbstractSitePerformanceResult $result
     * @return string
     */
    public function renderLabConfirmed(AbstractSitePerformanceResult $result)
    {
        $classification = $result->hasMinimumLabConfirmed();

        $color = $this->getClassificationColor($classification);

        if ($classification !== AbstractSitePerformanceResult::BAD) {
            $color = ($classification == AbstractSitePerformanceResult::GOOD ? 'infobox-green' : 'infobox-orange');
        }
        $params = array(
            'includeInfobox' => true,
            'percent' => (int)$result->getLabConfirmedPercent(),
            'value'=>$result->getLabConfirmed(),
            'color' => $color,
            'classification'=>$classification,
            'classificationString'=>$result->getMinimumLabConfirmedString(),
        );
        return $this->twig->render('NSSentinelBundle:Report:infobox.html.twig', $params);
    }

    /**
     * @param $classification
     * @return string
     */
    public function getClassificationColor($classification)
    {
        $color = 'infobox-blue2';
        if ($classification !== AbstractSitePerformanceResult::BAD) {
            $color = ($classification == AbstractSitePerformanceResult::GOOD ? 'infobox-green' : 'infobox-orange');
        }

        return $color;
    }
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'SitePerformanceTwigExtension';
    }
}

/*    public function renderMinimumCasesFlag(AbstractSitePerformanceResult $result)
    {
        $color = 'infobox-blue2';
        $classification = $result->getMinimumNumberOfCases();
        if($classification !== AbstractSitePerformanceResult::BAD) {
            $color = ($classification == AbstractSitePerformanceResult::GOOD ? 'infobox-green' : 'infobox-orange');
        }
        $params = array(
            'includeInfobox' => false,
//            'percent' => (int)$result->getMinimumNumberOfCasesPercent(),//ConsistentReportingPercent(),
            'value'=>$result->getTotalCases(),
            'color' => $color,
            'classification'=>$classification,
            'classificationString'=>$result->getMinimumNumberOfCasesString(),
        );

        return $this->twig->render('NSSentinelBundle:Report:infobox.html.twig',$params);
    }

    public function renderConsistentReportingFlag(AbstractSitePerformanceResult $result)
    {
        $color = 'infobox-blue2';
        $classification = $result->getConsistentReporting();
        if($classification !== AbstractSitePerformanceResult::BAD) {
            $color = ($classification == AbstractSitePerformanceResult::GOOD ? 'infobox-green' : 'infobox-orange');
        }
        $params = array(
            'includeInfobox' => false,
//            'percent' => (int)$result->getConsistentReportingPercent(),
            'value'=>$result->getConsistentReportingCount(),
            'color' => $color,
            'classification'=>$classification,
            'classificationString'=>AbstractSitePerformanceResult::CONSISTENT_REPORTING_STR,
        );

        return $this->twig->render('NSSentinelBundle:Report:infobox-months.html.twig',$params);
    }
*/
