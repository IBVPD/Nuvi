<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Form\Type\TripleChoice;

use NS\SentinelBundle\Form\Type\CSFAppearance;
use NS\SentinelBundle\Form\Type\CXRResult;
use NS\SentinelBundle\Form\Type\Diagnosis;
use NS\SentinelBundle\Form\Type\DischargeOutcome;
use NS\SentinelBundle\Form\Type\Doses;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

/**
 * Description of Meningitis
 * @author gnat
 * @ORM\Entity
 * @ORM\Table(name="meningitis_cases")
 * @Gedmo\Loggable
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
     * @ORM\Column(name="csfAppearance",type="CSFAppearance",nullable=true)
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
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getDob() {
        return $this->dob;
    }

    public function setDob($dob) {
        $this->dob = $dob;
        return $this;
    }

    public function getAgeInMonths() {
        return $this->ageInMonths;
    }

    public function setAgeInMonths($ageInMonths) {
        $this->ageInMonths = $ageInMonths;
        return $this;
    }

    public function getHibReceived() {
        return $this->hibReceived;
    }

    public function setHibReceived(TripleChoice $hibReceived) {
        $this->hibReceived = $hibReceived;
        return $this;
    }

    public function getHibDoses() {
        return $this->hibDoses;
    }

    public function setHibDoses(Doses $hibDoses) {
        $this->hibDoses = $hibDoses;
        return $this;
    }

    public function getPcvReceived() {
        return $this->pcvReceived;
    }

    public function setPcvReceived(TripleChoice $pcvReceived) {
        $this->pcvReceived = $pcvReceived;
        return $this;
    }

    public function getPcvDoses() {
        return $this->pcvDoses;
    }

    public function setPcvDoses(Doses $pcvDoses) {
        $this->pcvDoses = $pcvDoses;
        return $this;
    }

    public function getMeningReceived() {
        return $this->meningReceived;
    }

    public function setMeningReceived(TripleChoice $meningReceived) {
        $this->meningReceived = $meningReceived;
        return $this;
    }

    public function getMeningDoses() {
        return $this->meningDoses;
    }

    public function setMeningDoses(Doses $meningDoses) {
        $this->meningDoses = $meningDoses;
        return $this;
    }

    public function getDtpReceived() {
        return $this->dtpReceived;
    }

    public function setDtpReceived(TripleChoice $dtpReceived) {
        $this->dtpReceived = $dtpReceived;
        return $this;
    }

    public function getDtpDoses() {
        return $this->dtpDoses;
    }

    public function setDtpDoses(Doses $dtpDoses) {
        $this->dtpDoses = $dtpDoses;
        return $this;
    }

    public function getAdmDate() {
        return $this->admDate;
    }

    public function setAdmDate($admDate) {
        $this->admDate = $admDate;
        return $this;
    }

    public function getAdmDx() {
        return $this->admDx;
    }

    public function setAdmDx(Diagnosis $admDx) {
        $this->admDx = $admDx;
        return $this;
    }

    public function getAdmDxOther() {
        return $this->admDxOther;
    }

    public function setAdmDxOther($admDxOther) {
        $this->admDxOther = $admDxOther;
        return $this;
    }

    public function getMenSeizures() {
        return $this->menSeizures;
    }

    public function setMenSeizures(TripleChoice $menSeizures) {
        $this->menSeizures = $menSeizures;
        return $this;
    }

    public function getMenFever() {
        return $this->menFever;
    }

    public function setMenFever(TripleChoice $menFever) {
        $this->menFever = $menFever;
        return $this;
    }

    public function getMenAltConscious() {
        return $this->menAltConscious;
    }

    public function setMenAltConscious(TripleChoice $menAltConscious) {
        $this->menAltConscious = $menAltConscious;
        return $this;
    }

    public function getMenInabilityFeed() {
        return $this->menInabilityFeed;
    }

    public function setMenInabilityFeed(TripleChoice $menInabilityFeed) {
        $this->menInabilityFeed = $menInabilityFeed;
        return $this;
    }

    public function getMenStridor() {
        return $this->menStridor;
    }

    public function setMenStridor(TripleChoice $menStridor) {
        $this->menStridor = $menStridor;
        return $this;
    }

    public function getMenNeckStiff() {
        return $this->menNeckStiff;
    }

    public function setMenNeckStiff(TripleChoice $menNeckStiff) {
        $this->menNeckStiff = $menNeckStiff;
        return $this;
    }

    public function getMenRash() {
        return $this->menRash;
    }

    public function setMenRash(TripleChoice $menRash) {
        $this->menRash = $menRash;
        return $this;
    }

    public function getMenFontanelleBulge() {
        return $this->menFontanelleBulge;
    }

    public function setMenFontanelleBulge(TripleChoice $menFontanelleBulge) {
        $this->menFontanelleBulge = $menFontanelleBulge;
        return $this;
    }

    public function getMenLethargy() {
        return $this->menLethargy;
    }

    public function setMenLethargy(TripleChoice $menLethargy) {
        $this->menLethargy = $menLethargy;
        return $this;
    }

    public function getMenPoorSucking() {
        return $this->menPoorSucking;
    }

    public function setMenPoorSucking(TripleChoice $menPoorSucking) {
        $this->menPoorSucking = $menPoorSucking;
        return $this;
    }

    public function getMenIrritability() {
        return $this->menIrritability;
    }

    public function setMenIrritability(TripleChoice $menIrritability) {
        $this->menIrritability = $menIrritability;
        return $this;
    }

    public function getMenSymptomOther() {
        return $this->menSymptomOther;
    }

    public function setMenSymptomOther($menSymptomOther) {
        $this->menSymptomOther = $menSymptomOther;
        return $this;
    }

    public function getPneuDiffBreathe() {
        return $this->pneuDiffBreathe;
    }

    public function setPneuDiffBreathe(TripleChoice $pneuDiffBreathe) {
        $this->pneuDiffBreathe = $pneuDiffBreathe;
        return $this;
    }

    public function getPneuChestIndraw() {
        return $this->pneuChestIndraw;
    }

    public function setPneuChestIndraw(TripleChoice $pneuChestIndraw) {
        $this->pneuChestIndraw = $pneuChestIndraw;
        return $this;
    }

    public function getPneuCough() {
        return $this->pneuCough;
    }

    public function setPneuCough(TripleChoice $pneuCough) {
        $this->pneuCough = $pneuCough;
        return $this;
    }

    public function getPneuCyanosis() {
        return $this->pneuCyanosis;
    }

    public function setPneuCyanosis(TripleChoice $pneuCyanosis) {
        $this->pneuCyanosis = $pneuCyanosis;
        return $this;
    }

    public function getPneuRespRate() {
        return $this->pneuRespRate;
    }

    public function setPneuRespRate($pneuRespRate) {
        $this->pneuRespRate = $pneuRespRate;
        return $this;
    }

    public function getPneuSymptomOther() {
        return $this->pneuSymptomOther;
    }

    public function setPneuSymptomOther($pneuSymptomOther) {
        $this->pneuSymptomOther = $pneuSymptomOther;
        return $this;
    }

    public function getCsfCollected() {
        return $this->csfCollected;
    }

    public function setCsfCollected($csfCollected) {
        $this->csfCollected = $csfCollected;
        return $this;
    }

    public function getCsfId() {
        return $this->csfId;
    }

    public function setCsfId($csfId) {
        $this->csfId = $csfId;
        return $this;
    }

    public function getCsfCollectDateTime() {
        return $this->csfCollectDateTime;
    }

    public function setCsfCollectDateTime($csfCollectDateTime) {
        $this->csfCollectDateTime = $csfCollectDateTime;
        return $this;
    }

    public function getCsfAppearance() {
        return $this->csfAppearance;
    }

    public function setCsfAppearance(CSFAppearance $csfAppearance) {
        $this->csfAppearance = $csfAppearance;
        return $this;
    }

    public function getCsfLabDateTime() {
        return $this->csfLabDateTime;
    }

    public function setCsfLabDateTime( $csfLabDateTime) {
        $this->csfLabDateTime = $csfLabDateTime;
        return $this;
    }

    public function getBloodCollected() {
        return $this->bloodCollected;
    }

    public function setBloodCollected($bloodCollected) {
        $this->bloodCollected = $bloodCollected;
        return $this;
    }

    public function getBloodId() {
        return $this->bloodId;
    }

    public function setBloodId($bloodId) {
        $this->bloodId = $bloodId;
        return $this;
    }

    public function getCsfWcc() {
        return $this->csfWcc;
    }

    public function setCsfWcc($csfWcc) {
        $this->csfWcc = $csfWcc;
        return $this;
    }

    public function getCsfGlucose() {
        return $this->csfGlucose;
    }

    public function setCsfGlucose($csfGlucose) {
        $this->csfGlucose = $csfGlucose;
        return $this;
    }

    public function getCsfProtein() {
        return $this->csfProtein;
    }

    public function setCsfProtein($csfProtein) {
        $this->csfProtein = $csfProtein;
        return $this;
    }

    public function getCsfCultDone() {
        return $this->csfCultDone;
    }

    public function setCsfCultDone(TripleChoice $csfCultDone) {
        $this->csfCultDone = $csfCultDone;
        return $this;
    }

    public function getCsfGramDone() {
        return $this->csfGramDone;
    }

    public function setCsfGramDone(TripleChoice $csfGramDone) {
        $this->csfGramDone = $csfGramDone;
        return $this;
    }

    public function getCsfBinaxDone() {
        return $this->csfBinaxDone;
    }

    public function setCsfBinaxDone(TripleChoice $csfBinaxDone) {
        $this->csfBinaxDone = $csfBinaxDone;
        return $this;
    }

    public function getCsfLatDone() {
        return $this->csfLatDone;
    }

    public function setCsfLatDone(TripleChoice $csfLatDone) {
        $this->csfLatDone = $csfLatDone;
        return $this;
    }

    public function getCsfPcrDone() {
        return $this->csfPcrDone;
    }

    public function setCsfPcrDone(TripleChoice $csfPcrDone) {
        $this->csfPcrDone = $csfPcrDone;
        return $this;
    }

    public function getBloodCultDone() {
        return $this->bloodCultDone;
    }

    public function setBloodCultDone(TripleChoice $bloodCultDone) {
        $this->bloodCultDone = $bloodCultDone;
        return $this;
    }

    public function getBloodGramDone() {
        return $this->bloodGramDone;
    }

    public function setBloodGramDone(TripleChoice $bloodGramDone) {
        $this->bloodGramDone = $bloodGramDone;
        return $this;
    }

    public function getBloodPcrDone() {
        return $this->bloodPcrDone;
    }

    public function setBloodPcrDone(TripleChoice $bloodPcrDone) {
        $this->bloodPcrDone = $bloodPcrDone;
        return $this;
    }

    public function getOtherCultDone() {
        return $this->otherCultDone;
    }

    public function setOtherCultDone( $otherCultDone) {
        $this->otherCultDone = $otherCultDone;
        return $this;
    }

    public function getOtherTestDone() {
        return $this->otherTestDone;
    }

    public function setOtherTestDone(TripleChoice $otherTestDone) {
        $this->otherTestDone = $otherTestDone;
        return $this;
    }

    public function getCsfCultResult() {
        return $this->csfCultResult;
    }

    public function setCsfCultResult($csfCultResult) {
        $this->csfCultResult = $csfCultResult;
        return $this;
    }

    public function getCsfCultOther() {
        return $this->csfCultOther;
    }

    public function setCsfCultOther($csfCultOther) {
        $this->csfCultOther = $csfCultOther;
        return $this;
    }

    public function getCsfGramResult() {
        return $this->csfGramResult;
    }

    public function setCsfGramResult($csfGramResult) {
        $this->csfGramResult = $csfGramResult;
        return $this;
    }

    public function getCsfBinaxResult() {
        return $this->csfBinaxResult;
    }

    public function setCsfBinaxResult($csfBinaxResult) {
        $this->csfBinaxResult = $csfBinaxResult;
        return $this;
    }

    public function getCsfLatResult() {
        return $this->csfLatResult;
    }

    public function setCsfLatResult($csfLatResult) {
        $this->csfLatResult = $csfLatResult;
        return $this;
    }

    public function getCsfLatOther() {
        return $this->csfLatOther;
    }

    public function setCsfLatOther($csfLatOther) {
        $this->csfLatOther = $csfLatOther;
        return $this;
    }

    public function getCsfPcrResult() {
        return $this->csfPcrResult;
    }

    public function setCsfPcrResult($csfPcrResult) {
        $this->csfPcrResult = $csfPcrResult;
        return $this;
    }

    public function getBloodCultResult() {
        return $this->bloodCultResult;
    }

    public function setBloodCultResult($bloodCultResult) {
        $this->bloodCultResult = $bloodCultResult;
        return $this;
    }

    public function getBloodCultOther() {
        return $this->bloodCultOther;
    }

    public function setBloodCultOther($bloodCultOther) {
        $this->bloodCultOther = $bloodCultOther;
        return $this;
    }

    public function getBloodGramResult() {
        return $this->bloodGramResult;
    }

    public function setBloodGramResult($bloodGramResult) {
        $this->bloodGramResult = $bloodGramResult;
        return $this;
    }

    public function getBloodPcrResult() {
        return $this->bloodPcrResult;
    }

    public function setBloodPcrResult($bloodPcrResult) {
        $this->bloodPcrResult = $bloodPcrResult;
        return $this;
    }

    public function getOtherCultResult() {
        return $this->otherCultResult;
    }

    public function setOtherCultResult($otherCultResult) {
        $this->otherCultResult = $otherCultResult;
        return $this;
    }

    public function getOtherCultOther() {
        return $this->otherCultOther;
    }

    public function setOtherCultOther($otherCultOther) {
        $this->otherCultOther = $otherCultOther;
        return $this;
    }

    public function getOtherTestResult() {
        return $this->otherTestResult;
    }

    public function setOtherTestResult($otherTestResult) {
        $this->otherTestResult = $otherTestResult;
        return $this;
    }

    public function getOtherTestOther() {
        return $this->otherTestOther;
    }

    public function setOtherTestOther($otherTestOther) {
        $this->otherTestOther = $otherTestOther;
        return $this;
    }

    public function getRrlCsfDate() {
        return $this->rrlCsfDate;
    }

    public function setRrlCsfDate($rrlCsfDate) {
        $this->rrlCsfDate = $rrlCsfDate;
        return $this;
    }

    public function getRrlIsolDate() {
        return $this->rrlIsolDate;
    }

    public function setRrlIsolDate($rrlIsolDate) {
        $this->rrlIsolDate = $rrlIsolDate;
        return $this;
    }

    public function getCsfStore() {
        return $this->csfStore;
    }

    public function setCsfStore($csfStore) {
        $this->csfStore = $csfStore;
        return $this;
    }

    public function getIsolStore() {
        return $this->isolStore;
    }

    public function setIsolStore($isolStore) {
        $this->isolStore = $isolStore;
        return $this;
    }

    public function getRrlName() {
        return $this->rrlName;
    }

    public function setRrlName($rrlName) {
        $this->rrlName = $rrlName;
        return $this;
    }

    public function getSpnSerotype() {
        return $this->spnSerotype;
    }

    public function setSpnSerotype($spnSerotype) {
        $this->spnSerotype = $spnSerotype;
        return $this;
    }

    public function getHiSerotype() {
        return $this->hiSerotype;
    }

    public function setHiSerotype($hiSerotype) {
        $this->hiSerotype = $hiSerotype;
        return $this;
    }

    public function getNmSerogroup() {
        return $this->nmSerogroup;
    }

    public function setNmSerogroup($nmSerogroup) {
        $this->nmSerogroup = $nmSerogroup;
        return $this;
    }

    public function getCxrDone() {
        return $this->cxrDone;
    }

    public function setCxrDone(TripleChoice $cxrDone) {
        $this->cxrDone = $cxrDone;
        return $this;
    }

    public function getCxrResult() {
        return $this->cxrResult;
    }

    public function setCxrResult(CXRResult $cxrResult) {
        $this->cxrResult = $cxrResult;
        return $this;
    }

    public function getDischOutcome() {
        return $this->dischOutcome;
    }

    public function setDischOutcome($dischOutcome) {
        $this->dischOutcome = $dischOutcome;
        return $this;
    }

    public function getDischDx() {
        return $this->dischDx;
    }

    public function setDischDx(Diagnosis $dischDx) {
        $this->dischDx = $dischDx;
        return $this;
    }

    public function getDischDxOther() {
        return $this->dischDxOther;
    }

    public function setDischDxOther($dischDxOther) {
        $this->dischDxOther = $dischDxOther;
        return $this;
    }

    public function getDischSequelae() {
        return $this->dischSequelae;
    }

    public function setDischSequelae(TripleChoice $dischSequelae) {
        $this->dischSequelae = $dischSequelae;
        return $this;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
        return $this;
    }
}