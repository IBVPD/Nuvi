<?php

namespace NS\SentinelBundle\Services;

use \Doctrine\Common\Collections\ArrayCollection;
use \Doctrine\Common\Persistence\ObjectManager;
use \Doctrine\ORM\Query;
use \Exporter\Source\ArraySourceIterator;
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
            return $this->export($result->all(),'csv');

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
                $results[$case['theYear']] = array('year'=>$case['theYear'],1=>0,2=>0,3=>0,4=>0, -1=>0);

            $results[$case['theYear']][$case[0]->getAgeDistribution()]++;
        }

        if($export)
            return $this->export($results,'xls');

        return array('results'=>$results,'form'=>$form->createView());
    }

    public function getFieldPopulation(Request $request, FormInterface $form, $redirectRoute)
    {
        $year   = date('Y');
        $from   = \DateTime::createFromFormat("Y-m-d H:i:s", sprintf("%d-1-1 00:00:00",$year-1));
        $to     = \DateTime::createFromFormat("Y-m-d H:i:s", sprintf("%d-12-31 23:59:59",$year-1));
        $export = false;
        $alias  = 'i';

        $qb = $this->em->getRepository('NSSentinelBundle:Site')->getWithCasesForDate($alias, $from, $to);
        $form->handleRequest($request);

        if($form->isValid())
        {
            if($form->get('reset')->isClicked())
                return new RedirectResponse($this->router->generate($redirectRoute));
            else
                $this->filter->addFilterConditions($form, $qb, $alias);

            $export = ($form->get('export')->isClicked());
        }

        $results = new ArrayCollection();
        $sites   = $qb->getQuery()->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)->getResult();

        foreach($sites as $x => $values)
        {
            $fpr = new \NS\SentinelBundle\Result\FieldPopulationResult();
            $fpr->setSite($values[0]->getSite());
            $fpr->setTotalCases($values['totalCases']);

            $results->set($fpr->getSite()->getCode(),$fpr);
        }

        $ibdRepo      = $this->em->getRepository('NSSentinelBundle:IBD');

        $csfCollected = $ibdRepo->getCsfCollectedCountBySites($results->getKeys(),$from,$to)->getQuery()->getResult(Query::HYDRATE_SCALAR);
        foreach($csfCollected as $c)
        {
            $fpr = $results->get($c['code']);
            if($fpr) // this should always be true.
                $fpr->setCsfCollectedCount($c['csfCollectedCount']);
        }

        $bloodCollected = $ibdRepo->getBloodCollectedCountBySites($results->getKeys(),$from,$to)->getQuery()->getResult(Query::HYDRATE_SCALAR);
        foreach($bloodCollected as $c)
        {
            $fpr = $results->get($c['code']);
            if($fpr) // this should always be true.
                $fpr->setBloodCollectedCount($c['bloodCollectedCount']);
        }

        $bloodResultCount = $ibdRepo->getBloodResultCountBySites($results->getKeys(),$from,$to)->getQuery()->getResult(Query::HYDRATE_SCALAR);
        foreach($bloodResultCount as $c)
        {
            $fpr = $results->get($c['code']);
            if($fpr) // this should always be true.
                $fpr->setBloodResultCount($c['bloodResultCount']);
        }

        $csfBinaxDoneCount = $ibdRepo->getCsfBinaxDoneCountBySites($results->getKeys(),$from,$to)->getQuery()->getResult(Query::HYDRATE_SCALAR);
        foreach($csfBinaxDoneCount as $c)
        {
            $fpr = $results->get($c['code']);
            if($fpr) // this should always be true.
                $fpr->setCsfBinaxDoneCount($c['csfBinaxDone']);
        }

        $csfBinaxResultCount = $ibdRepo->getCsfBinaxResultCountBySites($results->getKeys(),$from,$to)->getQuery()->getResult(Query::HYDRATE_SCALAR);
        foreach($csfBinaxResultCount as $c)
        {
            $fpr = $results->get($c['code']);
            if($fpr) // this should always be true.
                $fpr->setCsfBinaxResultCount($c['csfBinaxResult']);
        }

        $csfLatDoneCount = $ibdRepo->getCsfLatDoneCountBySites($results->getKeys(),$from,$to)->getQuery()->getResult(Query::HYDRATE_SCALAR);
        foreach($csfLatDoneCount as $c)
        {
            $fpr = $results->get($c['code']);
            if($fpr) // this should always be true.
                $fpr->setCsfLatDoneCount($c['csfLatDone']);
        }

        $csfLatResultCount = $ibdRepo->getCsfLatResultCountBySites($results->getKeys(),$from,$to)->getQuery()->getResult(Query::HYDRATE_SCALAR);
        foreach($csfLatResultCount as $c)
        {
            $fpr = $results->get($c['code']);
            if($fpr) // this should always be true.
                $fpr->setCsfLatResultCount($c['csfLatResult']);
        }

        if($export)
            return $this->export($sites);

        return array('sites' => $results, 'form' => $form->createView(),'csfCollected'=>$csfCollected);
    }

    public function export($results, $format='csv')
    {
        $source   = new ArraySourceIterator($results);
        $filename = sprintf('export_%s.%s',date('Y_m_d_H_i_s'), $format);

        return $this->exporter->getResponse($format, $filename, $source);
    }

//*first I dropped all sites without 2013 data
//drop if  annualcase2013==. |  annualcase2013==0
//*71 sites were dropped
//
//*I created a variable called "ncountry" to count the number of countries within a region
//sort  ISO3_code
//by  ISO3_code: gen ncountry=_n==1
//sort  site_code
//by  site_code: gen nsite=_n==1
//*first to count countries and sites within a region
//table  ncountry  Region
//table nsite Region
//
//*to count sites with support:
//by  IBVPD2014IntenseSupport, sort: table  nsite Region
//
//*to count countries and sites within a region that introduced vaccine and did not introduce vaccine
//*I first converted "Pneumo" to a numeric variable
//replace Pneumo="0" if  Pneumo=="n/a"
//replace Pneumo="0" if  Pneumo==""
//destring Pneumo, replace
//table  Pneumo ncountry
//by Pneumo, sort: table ncountry Region
//
//*next I looked only at sites that had received intense support
//*I had to recode my variable
//replace  IBVPD2014IntenseSupport="1" if  IBVPD2014IntenseSupport=="Yes"
//replace  IBVPD2014IntenseSupport="0" if  IBVPD2014IntenseSupport=="No"
//codebook IBVPD2014IntenseSupport
//replace  IBVPD2014IntenseSupport="0" if  IBVPD2014IntenseSupport=="No (But monitored by WHO)"
//replace  IBVPD2014IntenseSupport="0" if  IBVPD2014IntenseSupport=="TBD Q3"
//destring IBVPD2014IntenseSupport, replace
//*next I dropped those that did not receive support
//preserve
//drop if IBVPD2014IntenseSupport!=1
//
//*to count the number of sites that have introduced vaccine
//count if Pneumo==0
//count if Pneumo!=0
//*I got 71 unique values forsites
//*I also generated a unique variable called anypneumo
//gen anyPneumo=0
//replace anyPneumo=1 if Pneumo>0
//codebook anyPneumo
//
//*I had to drop my old "ncountry" variable and create a new one
//drop  ncountry nsite
//sort  ISO3_code
//by  ISO3_code: gen ncountry=_n==1
//sort  site_code
//by  site_code: gen nsite=_n==1
//
//table  Pneumo ncountry
//by Pneumo, sort: table ncountry Region
//drop ncountry
//*I can then restore to get all of my information back
//restore
//
//*then I looked at sites that had enough data pre-vaccine by year
//replace  annualcase2012=0 if  annualcase2012==.
//replace  annualcase2013=0 if  annualcase2013==.
//replace  annualcase2011=0 if  annualcase2011==.
//replace  annualcase2010=0 if  annualcase2010==.
//replace  annualcase2009=0 if  annualcase2009==.
//replace  annualcase2008=0 if  annualcase2008==.
//replace  annualcase2007=0 if  annualcase2007==.
//replace  annualcase2006=0 if  annualcase2006==.
//
//save "/Users/destmaam/Desktop/WHO practicum/IBD consistency STATA files/intermediate file/2013 data analysis file", replace
//
//*to figure out which sites met criteria for 2 years before and any data for 1 year after I did the following
//list  Region ISO3_code site_code Hib if  Pneumo==2012 &  annualcase2013!=0 &   criteriametboth2011==1 &  criteriametboth2010==1
//list  Region ISO3_code site_code Hib if  Pneumo==2011 &  annualcase2012!=0 &   criteriametboth2010==1 &  criteriametboth2009==1
//list  Region ISO3_code site_code Hib if  Pneumo==2010 &  annualcase2011!=0 &   criteriametboth2009==1 &  criteriametboth2008==1
//list  Region ISO3_code site_code Hib if  Pneumo==2009 &  annualcase2010!=0 &   criteriametboth2008==1 &  criteriametboth2007==1
//*to figure out which sites met criteria for 1 year before and any data for 1 year after I did the following
//list  Region ISO3_code site_code Hib if  Pneumo==2012 &  annualcase2013!=0 &   criteriametboth2011==1
//list  Region ISO3_code site_code Hib if  Pneumo==2011 &  annualcase2012!=0 &   criteriametboth2010==1
//list  Region ISO3_code site_code Hib if  Pneumo==2010 &  annualcase2011!=0 &   criteriametboth2009==1
//list  Region ISO3_code site_code Hib if  Pneumo==2009 &  annualcase2010!=0 &   criteriametboth2008==1
//*for those that haven't introduced vaccine and have 2012 and 2013 data:
//list  Region ISO3_code site_code if  Pneumo==0 & criteriametboth2012==1 & criteriametboth2013==1
//*for those that haven't introduced vaccine and have 2013 data:
//list  Region ISO3_code site_code if  Pneumo==0 &   criteriametboth2013==1
//*I also checked those who introduced in 2013 and had data from 2012 and 2011
//list Region ISO3_code site_code if Pneumo==2013 &  criteriametboth2011==1 &  criteriametboth2012==1
//*and those who introduced in 2013 and had data in 2012
//list Region ISO3_code site_code if Pneumo==2013 &  criteriametboth2012==1
//
//*to create a list of sites with country information and number of cases and number of months under surveillance I dropped all the variables that were not case or month inofrmaiton
//preserve
//drop  criteriametboth0 criteriametannual0 criteriametmonth0 annualcase0 numbermonths0 criteriametboth2006 criteriametannual2006 criteriametmonth2006 criteriametboth2007 criteriametannual2007 criteriametmonth2007 criteriametboth2008 criteriametannual2008 criteriametmonth2008 criteriametboth2009 criteriametannual2009 criteriametmonth2009 criteriametboth2010 criteriametannual2010 criteriametmonth2010 criteriametboth2011 criteriametannual2011 criteriametmonth2011 criteriametboth2012 criteriametannual2012 criteriametmonth2012 criteriametboth2013 criteriametannual2013 criteriametmonth2013
//sort Region ISO3_code site_code
//export excel using "/Users/destmaam/Desktop/WHO practicum/IBD consistency excel file/merged region case and month data.xls", replace firstrow(variables)
//restore
//save "/Users/destmaam/Desktop/WHO practicum/IBD consistency STATA files/intermediate file/2013 data analysis file.dta", replace
//clear
//import excel "/Users/destmaam/Desktop/population under 5 revised.xlsx", sheet("UNDP_2012_rev_LT5y") firstrow
//*here I added in information about the population of children under 5
//merge m:m ISO3_code using "/Users/destmaam/Desktop/WHO practicum/IBD consistency STATA files/intermediate file/2013 data analysis file.dta"
//drop if _merge==1
//save "/Users/destmaam/Desktop/WHO practicum/IBD consistency STATA files/intermediate file/2013 data analysis file.dta", replace
//export excel using "/Users/destmaam/Desktop/WHO practicum/IBD consistency excel file/merged region case and month with population data.xls", replace firstrow(variables)
//
//*to see how many of the sites that met criteria (28) had complete information I did the following
//*first I created a dataset containing the names of the 28 sites and merged it with the original and dropped those that didn't match
//use "/Users/destmaam/Desktop/WHO practicum/IBD consistency STATA files/intermediate file/vaccine site names.dta"
//rename site site_code
//merge 1:m site_code using "/Users/destmaam/Desktop/WHO practicum/IBD consistency STATA files/intermediate file/modified 2008-2013 case data.dta"
//drop if  _merge!=3
//*now my dataset only contains those sites that met criteria for further analysis
//*to calculate the number of missing agemonths I first replaced all negative numbers to be a missing number
//replace  age_months=. if  age_months<0
//*I then created a variable for all those with complete ages and calculated the proportion of those whose ages were complete
//gen case=1
//sort site_code
//by site_code: egen totalcase=total(case)
//gen ageinmonthscomplete=1 if  age_months!=.
//replace ageinmonthscomplete=0 if age_months==.
//by site_code: egen totalageinmonthscomplete=total( ageinmonthscomplete)
//by site_code: gen propageinmonthscomplete= (totalageinmonthscomplete/ totalcase)*100
//
//*I then calculated the age of children in months based on birthdate and admission date
//gen calcage=(year(adm_date)-year(birthdate))*12+(month( adm_date)-month( birthdate))
//*I then looked at the completeness of the calcage variable
//gen calcagecomplete=1 if calcage!=.
//replace calcagecomplete=0 if calcage==.
//sort site_code
//by  site_code: egen totalcalcagecomplete=total(calcagecomplete)
//by site_code: gen proptotalcalcagecomplete= (totalcalcagecomplete/ totalcase)*100
//
//*I then compared the calcage variable to the age months variable given by the group
//gen ageequal=1 if round(age_months)== calcage
//*to figure out the proportion that were the same I did the following
//sort site_code
//by site_code:egen totalageequal=total(ageequal)
//*I then got the proportion of cases that had the same age
//by site_code: gen propageequal=(totalageequal/totalcase)*100
//
//*I created a variable to look at the number of children under age 5 and for those over 5
//gen ageunder5=.
//replace ageunder5=1 if  age_months!=. &  age_months<60
//codebook  age_months
//replace ageunder5=0 if age_months!=. & age_months>=60
//gen ageover5=.
//replace ageover5=1 if age_months!=. & age_months>=60
//replace ageover5=0 if age_months!=. & age_months<60
//*to calculate the proportion of children under 5 I created a new variable
//sort  site_code
//by site_code: egen totalageunder5=total( ageunder5)
//by site_code: gen propunder5=(totalageunder5/totalageinmonthscomplete)*100
//*next I wanted to look at data completion specifically for chilren <5
//drop if  age_months==. |  age_months>=60
//*first I looked at whether or not the children had an LP (using the variable that I had created previously)
//*I had to recount the number of cases to get my new denominator
//drop case totalcase
//gen case=1
//sort site_code
//by site_code: egen totalcase=total(case)
//sort site_code
//by site_code: egen LPdonetotal=total(LPdone)
//by site_code:gen proportionLPdone= (LPdonetotal/ totalcase)*100
//*I also looked at whether or not sites had completed the CSF collected variable in the database
//replace  CSF_collected=0 if  CSF_collected==.
//sort site_code
//by site_code: egen CSFcollectedtotal=total(CSF_collected)
//by site_code:gen proportionCSFcollected= (CSFcollectedtotal/ totalcase)*100
//*I then looked to see if my LP collected variable was similar to the CSF collected variable
//sort  site_code
//gen LPequal=1 if LPdone==CSF_collected
//by  site_code:egen totalLPequal=total( LPequal)
//by  site_code:gen propLPequal=(totalLPequal/ totalcase)*100
//*next I looked at the percentage of cases that had blood collected based on their variable
//replace  blood_collected=0 if  blood_collected==.
//sort site_code
//by site_code: egen totalbloodcollected=total( blood_collected)
//by site_code: gen propbloodcollected= (totalbloodcollected/ totalcase)*100
//*next I generated a variable for any blood draw result & calculated the percentage of of cases that had any results
//gen bloodresult=1 if  blood_cult_result!=. & blood_cult_result!=99
//replace bloodresult=1 if  blood_gram_stain!=. & blood_gram_stain!=99
//replace bloodresult=1 if  blood_gram_result!=. & blood_gram_result!=99
//replace bloodresult=1 if  blood_PCR_result!=. & blood_PCR_result!=99
//replace bloodresult=0 if bloodresult==.
//by  site_code:egen totalbloodresult=total(bloodresult)
//by  site_code:gen proptotalbloodresult=(totalbloodresult/ totalcase)*100
//*next I checked concordance with my bloodresult variable and the blood collected variable
//gen bloodequal=1 if  blood_collected== bloodresult
//by site_code: egen totalbloodequal=total(bloodequal)
//by site_code: gen propbloodequal= (totalbloodequal/ totalcase)*100
//browse
//*to check the proportion of those who had a culture result recorded out of those who had a culture done
//*I used LPdone as my denominator for the proportion of those with cultures
//gen CSFculturerecord=1 if  CSF_cult_result!=. & CSF_cult_result!=99
//by site_code: egen totalCSFcultrecorded=total(CSFculturerecord)
//by site_code: gen propCSFcultrecorded= (totalCSFcultrecorded/LPdonetotal)*100
//*to check the proportion of those with blood culture results recorded I did the following
//*I used bloodresult as my denominator
//gen bloodculturerecord=1 if  blood_cult_result!=. & blood_cult_result!=99
//by site_code: egen totalbloodcultrecorded=total(bloodculturerecord)
//by site_code: gen propbloodcultrecorded= (totalbloodcultrecorded/totalbloodresult)*100
//*I then calculated the number that had binax results recorded
//replace CSF_binax_done=. if CSF_binax_done==99
//by site_code: egen totalbinaxdone=total(CSF_binax_done)
//gen binaxrecord=1 if  CSF_binax_result!=. & CSF_binax_result!=99
//by site_code: egen totalbinaxrecorded=total(binaxrecord)
//by site_code: gen propbinaxrecorded= (totalbinaxrecorded/totalbinaxdone)*100
//*of note many of the sites had blanks for the CSF_binax_done variable
//*for the LAT test
//replace CSF_LAT_done=. if CSF_LAT_done==99
//by site_code: egen totalCSFlatdone=total(CSF_LAT_done)
//gen CSFlatrecord=1 if CSF_LAT_result!=. & CSF_LAT_result!=99
//by site_code: egen totalCSFlatrecorded=total(CSFlatrecord)
//by site_code: gen propCSFlatrecorded= (totalCSFlatrecorded/totalCSFlatdone)*100
//*of note many of these did not have CSF lat done recorded
//*for the PCR recorded variable
//replace CSF_PCR_done=. if CSF_PCR_done==99
//by site_code: egen totalCSFPCRdone=total(CSF_PCR_done)
//gen CSFPCRrecord=1 if CSF_PCR_result!=. & CSF_PCR_result!=99
//by site_code: egen totalCSFPCRrecorded=total(CSFPCRrecord)
//by site_code: gen propCSFPCRrecorded= (totalCSFPCRrecorded/totalCSFPCRdone)*100
//*I also looked at the total of sites that had CSF PCR that was positive for strep pneumo
//gen CSFspnpcrpositive=1 if CSF_PCR_result==1
//by site_code: egen totalCSFspnpcrpositive=total(CSFspnpcrpositive)
//*to look at the number with serotype results I first had to recode some of the alphanumeric variables
//
//replace  SPN_serotype= "" if  SPN_serotype=="inconclusive" |  SPN_serotype=="Negative"
//replace  SPN_serotype= "" if SPN_serotype=="SPN_serotype DNA not sufficient for serotyping"
//replace  SPN_serotype= "" if SPN_serotype=="DNA not sufficient for serotyping"
//replace SPN_serotype= "" if SPN_serotype=="DNA not sufficient for serotype"
//replace  SPN_serotype= "" if  SPN_serotype=="Serotype Neg"
//replace  SPN_serotype= "" if  SPN_serotype=="NEGATIVE."
//replace  SPN_serotype= "" if  SPN_serotype=="DNA not sufficient for typing."
//replace  SPN_serotype= "" if  SPN_serotype=="DNA not suffucient for serotyping."
//replace  SPN_serotype= "" if  SPN_serotype=="DNAnot sufficient for serotyping"
//replace  SPN_serotype= "" if  SPN_serotype=="negative for 42 sertypes"
//replace  SPN_serotype= "" if  SPN_serotype=="Insuficient for serotyping"
//replace  SPN_serotype= "" if  SPN_serotype=="NEG42"
//replace  SPN_serotype= "" if  SPN_serotype=="Neg42"
//replace  SPN_serotype= "" if  SPN_serotype=="inconlusive"
//replace  SPN_serotype= "" if  SPN_serotype=="negative"
//replace  SPN_serotype= "" if  SPN_serotype=="negative for 42 serotypes tested**"
//replace  SPN_serotype= "" if  SPN_serotype=="insufficient for DNAserotyping"
//replace  SPN_serotype= "" if  SPN_serotype=="negative for 42 serotypes tested"
//replace  SPN_serotype= "" if  SPN_serotype=="s pnuemo"
//replace  SPN_serotype= "" if  SPN_serotype=="S;PNEUMONIAE"
//replace  SPN_serotype= "" if  SPN_serotype=="DNA not suffucient for serotyping"
//replace  SPN_serotype= "" if  SPN_serotype=="DNAnot sufficient for serotyping"
//replace  SPN_serotype= "" if  SPN_serotype=="DNA not sufficient for typing."
//replace  SPN_serotype= "" if  SPN_serotype=="negative for 42 serotypes tested**"
//gen Spnserotyperesult=1 if  SPN_serotype!=""
//gen Spnresult=1 if  SPN_serotype!=""
//replace Spnresult=1 if   CSF_cult_result==1
//replace Spnresult=1 if  CSF_binax_result==1
//replace Spnresult=1 if  CSF_LAT_result==1
//replace Spnresult=1 if   CSF_PCR_result==1
//replace Spnresult=1 if  blood_cult_result==1
//replace Spnresult=1 if  blood_PCR_result==1
//browse if  Spnresult==1
//by site_code: egen totalSpnresult=total(Spnresult)
//by site_code: egen totalSpnserotyperesult=total(Spnserotyperesult)
//by site_code: gen propSpnresult=(totalSpnresult/totalcase)*100
//*to look at the number of serotypes for Hib
//gen HIresult=1 if  HI_serotype!=""
//replace HIresult=1 if  CSF_cult_result==2
//replace HIresult=1 if   CSF_LAT_result==2
//replace HIresult=1 if   CSF_PCR_result==2
//replace HIresult=1 if    blood_cult_result==2
//replace HIresult=1 if  blood_PCR_result ==2
//by site_code: egen totalHIresult=total(HIresult)
//gen HItype=1 if  HI_serotype!=""
//by site_code: egen totalHItype=total(HItype)
//*to look at the number with NM serogroups
//gen NMresult=1 if  NM_serogroup!=""
//replace  NMresult=1 if  CSF_cult_result==3
//replace  NMresult=1 if   CSF_LAT_result==3
//replace  NMresult=1 if    CSF_PCR_result==3
//replace NMresult=1 if    blood_cult_result==3
//replace NMresult=1 if  blood_PCR_result ==3
//*I also looked at those with an outcome recorded
//gen dischoutcomerecord=1 if  disch_outcome!=. &  disch_outcome!=99
//by site_code: egen totaldischoutcomerecorded=total( dischoutcomerecord)
//by site_code: gen probdischoutcomerecorded= (totaldischoutcomerecorded/ totalcase)*100
//browse
//browse  site_code Region disch_outcome dischoutcomerecord totaldischoutcomerecorded probdischoutcomerecorded
//*I collapsed so that I would have just one value for each site
//collapse (first) propageinmonthscomplete (first) proptotalcalcagecomplete (first) propunder5 (first) propageequal (first) totalCSFspnpcrpositive (first) proportionCSFcollected (first) proportionLPdone (first) propLPequal (first) propbloodcollected (first) proptotalbloodresult (first) propbloodequal (first) propCSFcultrecorded (first) propbloodcultrecorded (first) propbinaxrecorded (first) propCSFlatrecorded (first) totalCSFPCRrecorded (first) propCSFPCRrecorded (first)totalSpnresult (first) propSpnresult (first) totalHIresult (sum) NMresult (first) probdischoutcomerecorded (first) totalSpnserotyperesult, by(Region site_code)
//*I then exported all of these into an excel file
//export excel using "/Users/destmaam/Desktop/WHO practicum/IBD consistency excel file/2013 analysis of key sites.xls", replace firstrow(variables)
    public function getIBDFieldPopulation()
    {

    }
}
