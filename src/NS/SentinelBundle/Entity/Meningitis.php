<?php

namespace NS\SentinelBundle\Entity;

/**
 * Description of Meningitis
 *
 * @author gnat
 */
class Meningitis
{
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
     * @var TripleChoice $hibReceived
     * @ORM\Column(name="hibReceived",type="TripleChoice",nullable=true)
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
    private $admDx;
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

    private $csfCollected;
    private $csfId;

    /**
     * @var DateTime $csfCollectDateTime
     * @ORM\Column(name="admDate",type="datetime",nullable=true)
     */
    private $csfCollectDateTime;
    private $csfAppearance;
    /**
     * @var DateTime $csfLabDateTime
     * @ORM\Column(name="csfLabDateTime",type="datetime",nullable=true)
     */
    private $csfLabDateTime;
    private $csfLabTime;
    private $bloodCollected;
    private $bloodId;
//Case-based Laboratory Data
    private $csfWcc;
    private $csfGlucose;
    private $csfProtein;
    private $csfCultDone;
    private $csfGramDone;
    private $csfBinaxDone;
    private $csfLatDone;
    private $csfPcrDone;
    private $bloodCultDone;
    private $bloodGramDone;
    private $bloodPcrDone;
    private $otherCultDone;
    private $otherTestDone;
    private $csfCultResult;
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
    private $rrlName;
    private $spnSerotype;
    private $hiSerotype;
    private $nmSerogroup;
//PNEUMONIA / SEPSIS (In addition to above)
    private $cxrDone;
    private $cxrResult;
//Case-based Outcome Data
    private $dischOutcome;
    private $dischDx;
    private $dischDxOther;
    private $dischSequelae;
    private $comment;
    
}
