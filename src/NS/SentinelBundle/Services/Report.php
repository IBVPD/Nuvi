<?php

namespace NS\SentinelBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Query;
use Exporter\Source\ArraySourceIterator;
use Sonata\CoreBundle\Exporter\Exporter;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use NS\SentinelBundle\Form\Types\Diagnosis;

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

    public function __construct(Exporter $exporter, $filter, ObjectManager $em)
    {
        $this->exporter = $exporter;
        $this->filter   = $filter;
        $this->em       = $em;
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
                return $this->redirect ($this->generateUrl ('reportPercentEnrolled'));
            else
                $this->filter->addFilterConditions($form, $queryBuilder, $alias);

            $export = ($form->get('export')->isClicked());
        }

        $ibdResults   = $queryBuilder->getQuery()->getResult();
        $diagnosis    = new Diagnosis();

        $headers      = array('Month')+$diagnosis->getValues();
        $headerValues = array_fill_keys($diagnosis->getValues(),0);
        $results = array();

        foreach($ibdResults as $res)
        {
            $diagnosis = $res['admDx']->__toString();
            if(!isset($results[$res['CreatedMonth']]))
                $results[$res['CreatedMonth']] = $headerValues;

            $results[$res['CreatedMonth']][$diagnosis] = $res['admDxCount'];
        }

        if($export)
        {
            $format   = 'csv';
            $source   = new ArraySourceIterator($results,$headers);
            $filename = sprintf('export_%s.%s',date('Y_m_d_H_i_s'), $format);

            return $this->exporter->getResponse($format, $filename, $source);
        }

        return array('results' => $results, 'form' => $form->createView(),'headers'=>$headers);
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
                return $this->redirect ($this->generateUrl ($redirectRoute));
            else
                $this->filter->addFilterConditions($form, $qb, $alias);

            $export = ($form->get('export')->isClicked());

        }

        $r       = $qb->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getResult();
        $results = array();

        foreach($r as $case)
        {
            if(!isset($results[$case['theYear']]))
                $results[$case['theYear']] = array('year'=>$case['theYear'],1=>0,2=>0,3=>0,4=>0, -1=>0);

            $results[$case['theYear']][$case[0]->getAgeDistribution()]++;
        }

        if($export)
        {
            $headers  = array('year'=>'Year','0-5'=>1,'6-11'=>2,'12-23'=>3,'24-59'=>4,'Unknown'=>-1,);
            $format   = 'xls';
            $source   = new ArraySourceIterator($results,$headers);
            $filename = sprintf('export_%s.%s',date('Y_m_d_H_i_s'), $format);

            return $this->exporter->getResponse($format, $filename, $source);
        }

        return array('results'=>$results,'form'=>$form->createView());
    }
}
