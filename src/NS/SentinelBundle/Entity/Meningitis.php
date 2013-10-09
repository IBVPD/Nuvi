<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Form\Type\TripleChoice;

/**
 * Description of Meningitis
 *
 * @author gnat
 * @ORM\Entity
 * @ORM\Table(name="meningitis_cases")
 */
class Meningitis
{
    /**
     * @var integer $id
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id",type="integer")
     */
    private $id;

    /**
     * @var DateTime $dob
     * @ORM\Column(name="dob",type="date",nullable=true)
     */
    private $dob;
    /**
     * @var integer $ageInMonths
     * @ORM\Column(name="ageInMonths",type="integer",nullable=true)
     */
    private $ageInMonths;

//Case-based Vaccination History
    /**
     * @var TripleChoice $hibReceived
     * @ORM\Column(name="hibReceived",type="TripleChoice",nullable=true)
     */
    private $hibReceived;

    /**
     * @var Doses $hibDoses
     * @ORM\Column(name="hibDoses",type="Doses",nullable=true)
     */
    private $hibDoses;

    /**
     * @var TripleChoice $pcvReceived
     * @ORM\Column(name="pcvReceived",type="TripleChoice",nullable=true)
     */
    private $pcvReceived;

    /**
     * @var Doses $pcvDoses
     * @ORM\Column(name="pcvDoses",type="Doses",nullable=true)
     */
    private $pcvDoses;

    /**
     * @var TripleChoice $meningReceived
     * @ORM\Column(name="meningReceived",type="TripleChoice",nullable=true)
     */
    private $meningReceived;

    /**
     * @var Doses $meningDoses
     * @ORM\Column(name="meningDoses",type="Doses",nullable=true)
     */
    private $meningDoses;

    /**
     * @var TripleChoice $dtpReceived
     * @ORM\Column(name="dtpReceived",type="TripleChoice",nullable=true)
     */
    private $dtpReceived;

    /**
     * @var Doses $dtpDoses
     * @ORM\Column(name="dtpDoses",type="Doses",nullable=true)
     */
    private $dtpDoses;
    
//Case-based Clinical Data
    /**
     * @var DateTime $admDate
     * @ORM\Column(name="admDate",type="date",nullable=true)
     */
    private $admDate;
    
    /**
     * @var Diagnosis $admDx
     * @ORM\Column(name="admDx",type="Diagnosis",nullable=true)
     */
    private $admDx;

    /**
     * @var string $admDxOther
     * @ORM\Column(name="admDxOther",type="string",nullable=true)
     */
    private $admDxOther;
//MENINGITIS
    /**
     * @var TripleChoice $menSeizures
     * @ORM\Column(name="menSeizures",type="TripleChoice",nullable=true)
     */
    private $menSeizures;

    /**
     * @var TripleChoice $menFever
     * @ORM\Column(name="menFever",type="TripleChoice",nullable=true)
     */
    private $menFever;

    /**
     * @var TripleChoice $menAltConscious
     * @ORM\Column(name="menAltConscious",type="TripleChoice",nullable=true)
     */
    private $menAltConscious;

    /**
     * @var TripleChoice $menInabilityFeed
     * @ORM\Column(name="menInabilityFeed",type="TripleChoice",nullable=true)
     */
    private $menInabilityFeed;

    /**
     * @var TripleChoice $menStridor
     * @ORM\Column(name="menStridor",type="TripleChoice",nullable=true)
     */
    private $menStridor;

    /**
     * @var TripleChoice $menNeckStiff
     * @ORM\Column(name="menNeckStiff",type="TripleChoice",nullable=true)
     */
    private $menNeckStiff;

    /**
     * @var TripleChoice $menRash
     * @ORM\Column(name="menRash",type="TripleChoice",nullable=true)
     */
    private $menRash;

    /**
     * @var TripleChoice $menFontanelleBulge
     * @ORM\Column(name="menFontanelleBulge",type="TripleChoice",nullable=true)
     */
    private $menFontanelleBulge;

    /**
     * @var TripleChoice $menLethargy
     * @ORM\Column(name="menLethargy",type="TripleChoice",nullable=true)
     */
    private $menLethargy;

    /**
     * @var TripleChoice $menPoorSucking
     * @ORM\Column(name="menPoorSucking",type="TripleChoice",nullable=true)
     */
    private $menPoorSucking;

    /**
     * @var TripleChoice $menIrritability
     * @ORM\Column(name="menIrritability",type="TripleChoice",nullable=true)
     */
    private $menIrritability;

    /**
     * @var text $menSymptomOther
     * @ORM\Column(name="menSymptomOther",type="text",nullable=true)
     */
    private $menSymptomOther;
//PNEUMONIA / SEPSIS

    /**
     * @var TripleChoice $pneuDiffBreathe
     * @ORM\Column(name="pneuDiffBreathe",type="TripleChoice",nullable=true)
     */
    private $pneuDiffBreathe;

    /**
     * @var TripleChoice $pneuChestIndraw
     * @ORM\Column(name="pneuChestIndraw",type="TripleChoice",nullable=true)
     */
    private $pneuChestIndraw;

    /**
     * @var TripleChoice $pneuCough
     * @ORM\Column(name="pneuCough",type="TripleChoice",nullable=true)
     */
    private $pneuCough;

    /**
     * @var TripleChoice $pneuCyanosis
     * @ORM\Column(name="pneuCyanosis",type="TripleChoice",nullable=true)
     */
    private $pneuCyanosis;

    /**
     * @var integer $pneuRespRate
     * @ORM\Column(name="pneuRespRate",type="integer",nullable=true)
     */
    private $pneuRespRate;

    /**
     * @var text $pneuSymptomOther
     * @ORM\Column(name="pneuSymptomOther",type="text",nullable=true)
     */
    private $pneuSymptomOther;
//Case-based Specimen Collection Data
    
    /**
     * @var DateTime $csfCollected
     * @ORM\Column(name="csfCollected",type="boolean",nullable=true)
     */
    private $csfCollected;
    
    private $csfId;

    /**
     * @var DateTime $csfCollectDateTime
     * @ORM\Column(name="csfCollectDateTime",type="datetime",nullable=true)
     */
    private $csfCollectDateTime;
    
    /**
     * @var DateTime $csfAppearance
     * @ORM\Column(name="csfAppearance",type="date",nullable=true)
     */
    private $csfAppearance;
    /**
     * @var DateTime $csfLabDateTime
     * @ORM\Column(name="csfLabDateTime",type="datetime",nullable=true)
     */
    private $csfLabDateTime;
//    private $csfLabTime;
    
    /**
     * @var boolean $bloodCollected
     * @ORM\Column(name="bloodCollected", type="boolean",nullable=true)
     */
    private $bloodCollected;
    private $bloodId;
//Case-based Laboratory Data

    /**
     * @var boolean $csfWcc
     * @ORM\Column(name="csfWcc", type="integer",nullable=true)
     */
    private $csfWcc;
    
    /**
     * @var boolean $csfGlucose
     * @ORM\Column(name="csfGlucose", type="integer",nullable=true)
     */    
    private $csfGlucose;

    /**
     * @var boolean $csfProtein
     * @ORM\Column(name="csfProtein", type="integer",nullable=true)
     */    
    private $csfProtein;
    
    /**
     * @var TripleChoice $csfCultDone
     * @ORM\Column(name="csfCultDone",type="TripleChoice",nullable=true)
     */    
    private $csfCultDone;
    
    /**
     * @var TripleChoice $csfGramDone
     * @ORM\Column(name="csfGramDone",type="TripleChoice",nullable=true)
     */    
    private $csfGramDone;
    
    /**
     * @var TripleChoice $csfBinaxDone
     * @ORM\Column(name="csfBinaxDone",type="TripleChoice",nullable=true)
     */    
    private $csfBinaxDone;
    
    /**
     * @var TripleChoice $csfLatDone
     * @ORM\Column(name="csfLatDone",type="TripleChoice",nullable=true)
     */
    private $csfLatDone;
    
    /**
     * @var TripleChoice $csfPcrDone
     * @ORM\Column(name="csfPcrDone",type="TripleChoice",nullable=true)
     */    
    private $csfPcrDone;
    
    /**
     * @var TripleChoice $bloodCultDone
     * @ORM\Column(name="bloodCultDone",type="TripleChoice",nullable=true)
     */
    private $bloodCultDone;
    
    /**
     * @var TripleChoice $bloodGramDone
     * @ORM\Column(name="bloodGramDone",type="TripleChoice",nullable=true)
     */
    private $bloodGramDone;
    
    /**
     * @var TripleChoice $bloodPcrDone
     * @ORM\Column(name="bloodPcrDone",type="TripleChoice",nullable=true)
     */    
    private $bloodPcrDone;
    
    /**
     * @var TripleChoice $otherCultDone
     * @ORM\Column(name="otherCultDone",type="TripleChoice",nullable=true)
     */
    private $otherCultDone;
    
    /**
     * @var TripleChoice $otherTestDone
     * @ORM\Column(name="otherTestDone",type="TripleChoice",nullable=true)
     */
    private $otherTestDone;

    private $csfCultResult;
    
    /**
     * @var string $csfCultOther
     * @ORM\Column(name="csfCultOther",type="string",nullable=true)
     */
    private $csfCultOther;
    
    private $csfGramResult;
    private $csfBinaxResult;
    private $csfLatResult;
    private $csfLatOther;
    private $csfPcrResult;
    private $bloodCultResult;
    private $bloodCultOther;
    private $bloodGramResult;
    private $bloodPcrResult;
    private $otherCultResult;
    private $otherCultOther;
    private $otherTestResult;
    private $otherTestOther;
    /**
     * @var DateTime $rrlCsfDate
     * @ORM\Column(name="rrlCsfDate",type="date",nullable=true)
     */
    private $rrlCsfDate;

    /**
     * @var DateTime $rrlIsoDate
     * @ORM\Column(name="rrlIsoDate",type="date",nullable=true)
     */
    private $rrlIsolDate;
    private $csfStore;
    private $isolStore;
    /**
     * @var string $rrlName
     * @ORM\Column(name="rrlName",type="string",nullable=true)
     */    
    private $rrlName;
    
    /**
     * @var string $spnSerotype
     * @ORM\Column(name="spnSerotype",type="string",nullable=true)
     */
    private $spnSerotype;
    
    /**
     * @var string $hiSerotyoe
     * @ORM\Column(name="hiSerotyoe",type="string",nullable=true)
     */    
    private $hiSerotype;
    
    /**
     * @var string $nmSerogroup
     * @ORM\Column(name="nmSerogroup",type="string",nullable=true)
     */    
    private $nmSerogroup;
//PNEUMONIA / SEPSIS (In addition to above)
    /**
     * @var TripleChoice $cxrDone
     * @ORM\Column(name="cxrDone",type="TripleChoice",nullable=true)
     */    
    private $cxrDone;
        
    /**
     * @var CXRResult $cxrResult
     * @ORM\Column(name="cxrResult",type="CXRResult",nullable=true)
     */
    private $cxrResult;
//Case-based Outcome Data
    /**
     * @var DishchargeOutcome $dischOutcome
     * @ORM\Column(name="dischOutcome",type="DischargeOutcome",nullable=true)
     */
    private $dischOutcome;

    /**
     * @var Diagnosis $dischDx
     * @ORM\Column(name="dischDx",type="Diagnosis",nullable=true)
     */
    private $dischDx;
    private $dischDxOther;
    
    /**
     * @var TripleChoice $dischSequelae
     * @ORM\Column(name="dischSequelae",type="TripleChoice",nullable=true)
     */
    private $dischSequelae;
    
    /**
     * @var string $comment
     * @ORM\Column(name="comment",type="text",nullable=true)
     */
    private $comment;
}
