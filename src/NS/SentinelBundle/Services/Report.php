<?php

namespace NS\SentinelBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Query;
use Exporter\Source\ArraySourceIterator;
use Sonata\CoreBundle\Exporter\Exporter;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

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

        $result = new \NS\SentinelBundle\Result\NumberEnrolledResult();
        $result->load($queryBuilder->getQuery()->getResult());

        try{
        if($export)
        {
            $format   = 'csv';
            $source   = new ArraySourceIterator($result->all());

            return $this->exporter->getResponse($format, sprintf('export_%s.%s',date('Y_m_d_H_i_s'), $format), $source);
        }

        return array('results' => $result, 'form' => $form->createView());
        }catch(\Exception $e)
        {
            die("GOT EXCEPTION: ".$e->getMessage());
        }
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
