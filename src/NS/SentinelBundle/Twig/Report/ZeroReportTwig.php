<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 24/05/16
 * Time: 10:44 AM
 */

namespace NS\SentinelBundle\Twig\Report;


use NS\SentinelBundle\Report\Result\ZeroReportSiteResult;

class ZeroReportTwig extends \Twig_Extension
{
    /**
     * @inheritDoc
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('zero_report', array($this, 'renderZeroReport'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @param ZeroReportSiteResult $report
     * @param string $selectIndex
     * @param int $year
     * @param int $month
     * @return int|string
     */
    public function renderZeroReport(ZeroReportSiteResult $report, $selectIndex, $year, $month)
    {
        $result = $report->getCountOrZeroReport($year, $month);
        if (is_numeric($result)) {
            return $result;
        }

        $options = array(
            '' => array('label' => 'Please Select...'),
            'zero' => array('label' => 'Zero'),
            'non' => array('label' => 'Non Report'),
        );

        if (is_string($result) && isset($options[$result])) {
            $options[$result]['selected'] = true;
        }

        $opts = '';
        foreach ($options as $value => $option) {
            $opts .= sprintf('<option value="%s"%s>%s</option>', $value, (isset($option['selected'])) ? ' selected' : '', $option['label']);
        }

        return sprintf('<select name="zeroReport[%s][%s]">%s</select>', $report->getSite()->getCode(), $selectIndex, $opts);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'zero_report_twig';
    }
}
