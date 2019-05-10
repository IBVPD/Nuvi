<?php

namespace NS\SentinelBundle\Entity\Meningitis;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Form\IBD\Types\CaseResult;
use NS\SentinelBundle\Form\IBD\Types\Diagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeClassification;
use NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeOutcome;
use NS\SentinelBundle\Form\IBD\Types\OtherSpecimen;
use NS\SentinelBundle\Form\IBD\Types\PCVType;
use NS\SentinelBundle\Form\IBD\Types\VaccinationType;
use NS\SentinelBundle\Form\Meningitis\Types\CSFAppearance;
use NS\SentinelBundle\Form\Types\FourDoses;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\VaccinationReceived;
use NS\SentinelBundle\Validators as LocalAssert;
use NS\UtilBundle\Validator\Constraints\ArrayChoiceConstraint;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Meningitis\MeningitisRepository")
 * @ORM\Table(name="mening_cases",uniqueConstraints={@ORM\UniqueConstraint(name="mening_site_case_id_idx",columns={"site_id","case_id"})})
 * @ORM\EntityListeners(value={"NS\SentinelBundle\Entity\Listener\MeningitisListener"})
 *
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY","ROLE_RRL_LAB","ROLE_NL_LAB"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 *
 * @LocalAssert\GreaterThanDate(atPath="adm_date",lessThanField="onsetDate",greaterThanField="admDate",message="form.validation.admission-after-onset")
 * @LocalAssert\GreaterThanDate(atPath="onset_date",lessThanField="birthdate",greaterThanField="onsetDate",message="form.validation.onset-after-dob")
 * @LocalAssert\GreaterThanDate(lessThanField="admDate",greaterThanField="csfCollectDate",message="form.validation.admission-after-csf-collection")
 * @LocalAssert\GreaterThanDate(lessThanField="admDate",greaterThanField="bloodCollectDate",message="form.validation.admission-after-blood-collection")
 * @LocalAssert\RelatedField(sourceField="admDx",sourceValue={"1"},fields={"menSeizures","menFever","menAltConscious","menInabilityFeed","menNeckStiff","menRash","menFontanelleBulge","menLethargy"},message="field-is-required-due-to-adm-diagnosis")
 * @LocalAssert\PCV()
 *
 * @LocalAssert\Other(groups={"Completeness"},field="admDx",otherField="admDxOther",value="NS\SentinelBundle\Form\IBD\Types\Diagnosis::OTHER")
 * @LocalAssert\Other(groups={"Completeness"},field="antibiotics",otherField="antibioticName",value="NS\SentinelBundle\Form\Types\TripleChoice::YES")
 *
 * @LocalAssert\Other(groups={"Completeness"},field="dischDx",otherField="dischDxOther",value={"NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis::OTHER","NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis::OTHER_MENINGITIS","NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis::OTHER_PNEUMONIA"})
 * @LocalAssert\Other(groups={"Completeness"},field="dischClass",otherField="dischClassOther",value={"NS\SentinelBundle\Form\IBD\Types\DischargeClassification::CONFIRMED_OTHER"})
 *
 * @LocalAssert\Other(groups={"Completeness"}, field="csfCollected", otherField="csfCollectDate",value={"NS\SentinelBundle\Form\Types\TripleChoice::YES"})
 * @LocalAssert\Other(groups={"Completeness"}, field="csfCollected", otherField="csfCollectTime",value={"NS\SentinelBundle\Form\Types\TripleChoice::YES"})
 * @LocalAssert\Other(groups={"Completeness"}, field="csfCollected", otherField="csfAppearance",value={"NS\SentinelBundle\Form\Types\TripleChoice::YES"})
 *
 * @LocalAssert\Other(groups={"Completeness"}, field="bloodCollected", otherField="bloodCollectDate",value={"NS\SentinelBundle\Form\Types\TripleChoice::YES"})
 * @LocalAssert\Other(groups={"Completeness"}, field="bloodCollected", otherField="bloodCollectTime",value={"NS\SentinelBundle\Form\Types\TripleChoice::YES"})
 * @LocalAssert\Other(groups={"AMR+Completeness"},field="bloodCollected",otherField="bloodNumberOfSamples",value={"NS\SentinelBundle\Form\Types\TripleChoice::YES"})
 *
 * @LocalAssert\Other(groups={"Completeness"},field="hibReceived",otherField="hibDoses",value={"NS\SentinelBundle\Form\Types\VaccinationReceived::YES_HISTORY","NS\SentinelBundle\Form\Types\VaccinationReceived::YES_CARD"})
 * @LocalAssert\Other(groups={"Completeness"},field="hibReceived",otherField="hibMostRecentDose",value={"NS\SentinelBundle\Form\Types\VaccinationReceived::YES_HISTORY","NS\SentinelBundle\Form\Types\VaccinationReceived::YES_CARD"})
 *
 * @LocalAssert\Other(groups={"Completeness"},field="pcvReceived",otherField="pcvType",value={"NS\SentinelBundle\Form\Types\VaccinationReceived::YES_HISTORY","NS\SentinelBundle\Form\Types\VaccinationReceived::YES_CARD"})
 * @LocalAssert\Other(groups={"Completeness"},field="pcvReceived",otherField="pcvMostRecentDose",value={"NS\SentinelBundle\Form\Types\VaccinationReceived::YES_HISTORY","NS\SentinelBundle\Form\Types\VaccinationReceived::YES_CARD"})
 *
 * @LocalAssert\Other(groups={"Completeness"},field="meningReceived",otherField="meningType",value={"NS\SentinelBundle\Form\Types\VaccinationReceived::YES_HISTORY","NS\SentinelBundle\Form\Types\VaccinationReceived::YES_CARD"})
 * @LocalAssert\Other(groups={"Completeness"},field="meningReceived",otherField="meningDate",value={"NS\SentinelBundle\Form\Types\VaccinationReceived::YES_HISTORY","NS\SentinelBundle\Form\Types\VaccinationReceived::YES_CARD"})
 *
 * @LocalAssert\Other(
 *     groups={"ARF+Completeness","EMR+Completeness","EUR+Completeness","SEAR+Completeness","WPR+Completeness"},
 *     field="otherSpecimenCollected",
 *     otherField="otherSpecimenOther",
 *     value={"NS\SentinelBundle\Form\IBD\Types\OtherSpecimen::OTHER"})
 *
 * @Serializer\AccessorOrder("custom", custom = {"region.code", "country.code", "site.code", "case_id","firstName","lastName","parentalName","gender","dobKnown","birthdate","district","state","id","age_months","ageDistribution","adm_date",
 *     "adm_dx","adm_dx_other","onset_date","antibiotics",
 *     "men_seizures","men_fever","men_alt_conscious","men_inability_feed","men_neck_stiff","men_rash","men_fontanelle_bulge","men_lethargy","men_irritability","men_vomit","men_malnutrition",
 *     "hib_received","hib_doses","hib_most_recent_dose",
 *     "pcv_received","pcv_doses","pcv_type","pcv_most_recent_dose",
 *     "mening_received","mening_type","mening_date",
 *     "csf_collected","csf_collect_date","csf_collect_time","csf_appearance",
 *     "blood_collected","blood_collect_date","blood_collect_time",
 *     "blood_number_of_samples","blood_second_collect_date","blood_second_collect_time",
 *     "other_specimen_collected","other_specimen_other",
 *     "disch_outcome","disch_dx","disch_dx_other","disch_class","disch_class_other","comment","result",
 *     "siteLab.csf_id","siteLab.csf_lab_date","siteLab.csf_lab_time","siteLab.wcc","siteLab.glucose","siteLab.protein",
 *     "siteLab.csf_cult_done","siteLab.csf_cult_result","siteLab.csf_cult_other","siteLab.csf_cult_contaminant",
 *     "siteLab.csf_gram_done","siteLab.csf_gram_stain","siteLab.csf_gram_result","siteLab.csf_gram_other",
 *     "siteLab.csf_binax_done","siteLab.csf_binax_result","siteLab.csf_binax_other",
 *     "siteLab.csf_lat_done","siteLab.csf_lat_result","siteLab.csf_lat_other",
 *     "siteLab.csf_pcr_done","siteLab.csf_pcr_result","siteLab.csf_pcr_result_other",
 *     "siteLab.csf_store","siteLab.isol_store",
 *     "siteLab.blood_id","siteLab.blood_lab_date","siteLab.blood_lab_time",
 *     "siteLab.blood_cult_done","siteLab.blood_cult_result","siteLab.blood_cult_other",
 *     "siteLab.blood_gram_done","siteLab.blood_gram_stain","siteLab.blood_gram_result","siteLab.blood_gram_other",
 *     "siteLab.blood_pcr_done","siteLab.blood_pcr_result","siteLab.blood_pcr_other",
 *     "siteLab.blood_second_id","siteLab.blood_second_lab_date","siteLab.blood_second_lab_time",
 *     "siteLab.blood_second_cult_done","siteLab.blood_second_cult_result","siteLab.blood_second_cult_other",
 *     "siteLab.blood_second_gram_done","siteLab.blood_second_gram_stain","siteLab.blood_second_gram_result","siteLab.blood_second_gram_other",
 *     "siteLab.blood_second_pcr_done","siteLab.blood_second_pcr_result","siteLab.blood_second_pcr_result_other",
 *     "siteLab.other_id","siteLab.other_type","siteLab.other_lab_time",
 *     "siteLab.other_cult_done","siteLab.other_cult_result","siteLab.other_cult_other",
 *     "siteLab.other_test_done","siteLab.other_test_result","siteLab.other_test_other",
 *     "siteLab.nl_csf_sent","siteLab.nl_csf_date",
 *     "siteLab.nl_isol_csf_sent","siteLab.nl_csf_blood_date",
 *     "siteLab.nl_isol_blood_sent","siteLab.nl_isol_blood_date",
 *     "siteLab.nl_broth_sent","siteLab.nl_broth_date",
 *     "siteLab.nl_other_sent","siteLab.nl_other_date",
 *     "siteLab.rl_csf_sent","siteLab.rl_csf_date",
 *     "siteLab.rl_isol_csf_sent","siteLab.rl_csf_blood_date",
 *     "siteLab.rl_isol_blood_sent","siteLab.rl_isol_blood_date",
 *     "siteLab.rl_broth_sent","siteLab.rl_broth_date",
 *     "siteLab.rl_other_sent","siteLab.rl_other_date",
 *     "siteLab.updatedAt","siteLab.status",
 *     "siteLab.adequate","siteLab.elisaDone","siteLab.elisaKit","siteLab.elisaKitOther","siteLab.elisaLoadNumber","siteLab.elisaExpiryDate","siteLab.elisaTestDate","siteLab.elisaResult","siteLab.stored","siteLab.genotypingDate","siteLab.genotypingResultG","siteLab.genotypeResultP","siteLab.genotypingResultGSpecify","siteLab.genotypeResultPSpecify","siteLab.stoolSentToNL","siteLab.stoolSentToNLDate","siteLab.stoolSentTo3RRL","siteLab.stoolSentTo3RRLDate",
 *     "nationalLab.lab_id","nationalLab.dt_sample_recd","nationalLab.type_sample_recd","nationalLab.isolate_viable","nationalLab.isolate_type",
 *     "nationalLab.method_used_pathogen_identify","nationalLab.method_used_pathogen_identify_other",
 *     "nationalLab.method_used_st_sg","nationalLab.method_used_st_sg_other",
 *     "nationalLab.spn_lytA","nationalLab.nm_ctrA","nationalLab.nm_sodC","nationalLab.hi_hpd1","nationalLab.hi_hpd3","nationalLab.hi_bexA","nationalLab.humanDNA_RNAseP",
 *     "nationalLab.final_RL_result_detection","nationalLab.spn_serotype","nationalLab.hi_serotype","nationalLab.nm_serogroup",
 *     "nationalLab.rl_isol_csf_sent","nationalLab.rl_isol_csf_date",
 *     "nationalLab.rl_isol_blood_sent","nationalLab.rl_isol_blood_date","nationalLab.rl_other_sent","nationalLab.rl_other_date",
 *     "nationalLab.comment","nationalLab.status","nationalLab.createdAt","nationalLab.updatedAt",
 *     "referenceLab.lab_id","referenceLab.dt_sample_recd","referenceLab.type_sample_recd","referenceLab.isolate_viable","referenceLab.isolate_type",
 *     "referenceLab.method_used_pathogen_identify","referenceLab.method_used_pathogen_identify_other",
 *     "referenceLab.method_used_st_sg","referenceLab.method_used_st_sg_other",
 *     "referenceLab.spn_lytA","referenceLab.nm_ctrA","referenceLab.nm_sodC","referenceLab.hi_hpd1","referenceLab.hi_hpd3","referenceLab.hi_bexA","referenceLab.humanDNA_RNAseP",
 *     "referenceLab.final_RL_result_detection","referenceLab.spn_serotype","referenceLab.hi_serotype","referenceLab.nm_serogroup",
 *     "referenceLab.comment","referenceLab.status","referenceLab.createdAt","referenceLab.updatedAt"})
 */
class Meningitis extends BaseCase
{
    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Meningitis\NationalLab", mappedBy="caseFile", cascade={"persist","remove"}, orphanRemoval=true)
     * @Serializer\Groups({"delete"})
     */
    protected $nationalLab;

    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Meningitis\ReferenceLab", mappedBy="caseFile", cascade={"persist","remove"}, orphanRemoval=true)
     * @Serializer\Groups({"delete"})
     */
    protected $referenceLab;

    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Meningitis\SiteLab", mappedBy="caseFile", cascade={"persist","remove"}, orphanRemoval=true)
     * @Serializer\Groups({"delete"})
     */
    protected $siteLab;

    /**
     * @Serializer\Exclude()
     */
    protected $siteLabClass = SiteLab::class;

    /**
     * @Serializer\Exclude()
     */
    protected $referenceClass = ReferenceLab::class;

    /**
     * @Serializer\Exclude()
     */
    protected $nationalClass  = NationalLab::class;

//Case-based Clinical Data
    /**
     * @var DateTime|null
     * @ORM\Column(name="onset_date",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Assert\DateTime
     * @LocalAssert\NoFutureDate()
     * @Assert\NotBlank(groups={"Completeness"})
     */
    private $onset_date;

    /**
     * @var Diagnosis|null
     * @ORM\Column(name="adm_dx",type="Diagnosis",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"AMR","Completeness"})
     */
    private $adm_dx;

    /**
     * @var string|null
     * @ORM\Column(name="adm_dx_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $adm_dx_other;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="antibiotics",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $antibiotics;

    /**
     * @var string|null
     * @ORM\Column(name="antibiotic_name",type="string", nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $antibiotic_name;

//MENINGITIS
    /**
     * @var TripleChoice|null
     * @ORM\Column(name="men_seizures",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $men_seizures;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="men_fever",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $men_fever;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="men_alt_conscious",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $men_alt_conscious;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="men_inability_feed",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $men_inability_feed;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="men_neck_stiff",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $men_neck_stiff;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="men_rash",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $men_rash;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="men_fontanelle_bulge",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $men_fontanelle_bulge;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="men_lethargy",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $men_lethargy;

//PAHO Variables
    /**
     * @var TripleChoice|null
     * @ORM\Column(name="men_irritability",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $men_irritability;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="men_vomit",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $men_vomit;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="men_malnutrition",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $men_malnutrition;

//Case-based Vaccination History
    /**
     * @var VaccinationReceived|null
     * @ORM\Column(name="hib_received",type="VaccinationReceived",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"AMR", "Completeness"})
     */
    private $hib_received;

    /**
     * @var FourDoses|null
     * @ORM\Column(name="hib_doses",type="FourDoses",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $hib_doses;

    /**
     * @var DateTime|null

     * @ORM\Column(name="hib_most_recent_dose",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate()
     */
    private $hib_most_recent_dose;

    /**
     * @var VaccinationReceived|null
     * @ORM\Column(name="pcv_received",type="VaccinationReceived",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"AMR","Completeness"})
     */
    private $pcv_received;

    /**
     * @var FourDoses|null
     * @ORM\Column(name="pcv_doses",type="FourDoses",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pcv_doses;

    /**
     * @var PCVType|null
     * @ORM\Column(name="pcv_type",type="PCVType",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pcv_type;

    /**
     * @var DateTime|null
     * @ORM\Column(name="pcv_most_recent_dose",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate()
     */
    private $pcv_most_recent_dose;

    /**
     * @var VaccinationReceived|null
     * @ORM\Column(name="mening_received",type="VaccinationReceived",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"AMR", "Completeness"})
     */
    private $mening_received;

    /**
     * @var VaccinationType|null
     * @ORM\Column(name="mening_type",type="IBDVaccinationType",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $mening_type;

    /**
     * @var DateTime|null
     * @ORM\Column(name="mening_date",type="date",nullable=true)
     * @Assert\Date
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $mening_date;

//Case-based Specimen Collection Data

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="csf_collected",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $csf_collected;

    /**
     * @var DateTime|null
     * @ORM\Column(name="csf_collect_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $csf_collect_date;

    /**
     * @var DateTime|null
     * @ORM\Column(name="csf_collect_time",type="time",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'H:i:s'>")
     */
    private $csf_collect_time;

    /**
     * @var CSFAppearance|null
     * @ORM\Column(name="csf_appearance",type="CSFAppearance",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $csf_appearance;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="blood_collected", type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $blood_collected;

    /**
     * @var DateTime|null
     * @ORM\Column(name="blood_collect_date",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate()
     */
    private $blood_collect_date;

    /**
     * @var DateTime|null
     * @ORM\Column(name="blood_collect_time",type="time",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'H:i:s'>")
     */
    private $blood_collect_time;

    /**
     * @var OtherSpecimen|null
     * @ORM\Column(name="other_specimen_collected",type="OtherSpecimen",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"ARF+Completeness","EMR+Completeness","EUR+Completeness","SEAR+Completeness","WPR+Completeness"})
     */
    private $other_specimen_collected;

    /**
     * @var string|null
     * @ORM\Column(name="other_specimen_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $other_specimen_other;

//Case-based Outcome Data
    /**
     * @var DischargeOutcome|null
     * @ORM\Column(name="disch_outcome",type="IBDDischargeOutcome",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $disch_outcome;

    /**
     * @var DischargeDiagnosis|null
     * @ORM\Column(name="disch_dx",type="IBDDischargeDiagnosis",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $disch_dx;

    /**
     * @var string|null
     * @ORM\Column(name="disch_dx_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $disch_dx_other;

    /**
     * @var DischargeClassification|null
     * @ORM\Column(name="disch_class",type="IBDDischargeClassification",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $disch_class;

    /**
     * @var string|null
     * @ORM\Column(name="disch_class_other",type="string",nullable=true)
     */
    private $disch_class_other;

    /**
     * @var string|null
     * @ORM\Column(name="comment",type="text",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $comment;

    /**
     * @var CaseResult|null
     * @ORM\Column(name="result",type="CaseResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $result;

    //PAHO/AMR Specific Variables
    /**
     * @var int|null
     * @ORM\Column(name="blood_number_of_samples",type="integer",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_number_of_samples;

    /**
     * @var DateTime|null
     * @ORM\Column(name="blood_second_collect_date",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate()
     * @LocalAssert\SecondBlood(groups={"AMR+Completeness"})
     */
    private $blood_second_collect_date;

    /**
     * @var DateTime|null
     * @ORM\Column(name="blood_second_collect_time",type="time",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'H:i:s'>")
     * @LocalAssert\SecondBlood(groups={"AMR+Completeness"})
     */
    private $blood_second_collect_time;

    public function __construct()
    {
        parent::__construct();
        $this->result = new CaseResult(CaseResult::UNKNOWN);
        $this->adm_dx = new Diagnosis(Diagnosis::SUSPECTED_MENINGITIS);
    }

    public function getOnsetDate(): ?DateTime
    {
        return $this->onset_date;
    }

    public function getAdmDx(): ?Diagnosis
    {
        return $this->adm_dx;
    }

    public function getAdmDxOther(): ?string
    {
        return $this->adm_dx_other;
    }

    public function getAntibiotics(): ?TripleChoice
    {
        return $this->antibiotics;
    }

    public function getAntibioticName(): ?string
    {
        return $this->antibiotic_name;
    }

    public function getMenSeizures(): ?TripleChoice
    {
        return $this->men_seizures;
    }

    public function getMenFever(): ?TripleChoice
    {
        return $this->men_fever;
    }

    public function getMenAltConscious(): ?TripleChoice
    {
        return $this->men_alt_conscious;
    }

    public function getMenInabilityFeed(): ?TripleChoice
    {
        return $this->men_inability_feed;
    }

    public function getMenNeckStiff(): ?TripleChoice
    {
        return $this->men_neck_stiff;
    }

    public function getMenRash(): ?TripleChoice
    {
        return $this->men_rash;
    }

    public function getMenFontanelleBulge(): ?TripleChoice
    {
        return $this->men_fontanelle_bulge;
    }

    public function getMenLethargy(): ?TripleChoice
    {
        return $this->men_lethargy;
    }

    public function getHibReceived(): ?VaccinationReceived
    {
        return $this->hib_received;
    }

    public function getHibDoses(): ?FourDoses
    {
        return $this->hib_doses;
    }

    public function getHibMostRecentDose(): ?DateTime
    {
        return $this->hib_most_recent_dose;
    }

    public function getPcvReceived(): ?VaccinationReceived
    {
        return $this->pcv_received;
    }

    public function getPcvDoses(): ?FourDoses
    {
        return $this->pcv_doses;
    }

    public function getPcvType(): ?PCVType
    {
        return $this->pcv_type;
    }

    public function getPcvMostRecentDose(): ?DateTime
    {
        return $this->pcv_most_recent_dose;
    }

    public function getMeningReceived(): ?VaccinationReceived
    {
        return $this->mening_received;
    }

    public function getMeningType(): ?VaccinationType
    {
        return $this->mening_type;
    }

    public function getMeningDate(): ?DateTime
    {
        return $this->mening_date;
    }

    public function getCsfCollected(): ?TripleChoice
    {
        return $this->csf_collected;
    }

    public function getCsfCollectDate(): ?DateTime
    {
        return $this->csf_collect_date;
    }

    public function getCsfCollectTime(): ?DateTime
    {
        return $this->csf_collect_time;
    }

    public function getCsfAppearance(): ?CSFAppearance
    {
        return $this->csf_appearance;
    }

    public function getBloodCollected(): ?TripleChoice
    {
        return $this->blood_collected;
    }

    public function getBloodCollectDate(): ?DateTime
    {
        return $this->blood_collect_date;
    }

    public function getBloodCollectTime(): ?DateTime
    {
        return $this->blood_collect_time;
    }

    public function setBloodCollectTime(?DateTime $blood_collect_time = null): void
    {
        $this->blood_collect_time = $blood_collect_time;
    }

    public function getOtherSpecimenCollected(): ?OtherSpecimen
    {
        return $this->other_specimen_collected;
    }

    public function getOtherSpecimenOther(): ?string
    {
        return $this->other_specimen_other;
    }

    public function getDischOutcome(): ?DischargeOutcome
    {
        return $this->disch_outcome;
    }

    public function getDischDx(): ?DischargeDiagnosis
    {
        return $this->disch_dx;
    }

    public function getDischDxOther(): ?string
    {
        return $this->disch_dx_other;
    }

    public function getDischClass(): ?DischargeClassification
    {
        return $this->disch_class;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getResult(): ?CaseResult
    {
        return $this->result;
    }

    public function setOnsetDate(?DateTime $onsetDate = null): void
    {
        $this->onset_date = $onsetDate;
    }

    public function setAdmDx(?Diagnosis $adm_dx = null): void
    {
        $this->adm_dx = $adm_dx;
    }

    public function setAdmDxOther(?string $adm_dxOther): void
    {
        $this->adm_dx_other = $adm_dxOther;
    }

    public function setAntibiotics(?TripleChoice $antibiotics = null): void
    {
        $this->antibiotics = $antibiotics;
    }

    public function setAntibioticName(?string $antibiotic_name): void
    {
        $this->antibiotic_name = $antibiotic_name;
    }

    public function setMenSeizures(?TripleChoice $menSeizures = null): void
    {
        $this->men_seizures = $menSeizures;
    }

    public function setMenFever(?TripleChoice $menFever = null): void
    {
        $this->men_fever = $menFever;
    }

    public function setMenAltConscious(?TripleChoice $menAltConscious = null): void
    {
        $this->men_alt_conscious = $menAltConscious;
    }

    public function setMenInabilityFeed(?TripleChoice $menInabilityFeed = null): void
    {
        $this->men_inability_feed = $menInabilityFeed;
    }

    public function setMenNeckStiff(?TripleChoice $menNeckStiff = null): void
    {
        $this->men_neck_stiff = $menNeckStiff;
    }

    public function setMenRash(?TripleChoice $menRash = null): void
    {
        $this->men_rash = $menRash;
    }

    public function setMenFontanelleBulge(?TripleChoice $menFontanelleBulge = null): void
    {
        $this->men_fontanelle_bulge = $menFontanelleBulge;
    }

    public function setMenLethargy(?TripleChoice $menLethargy = null): void
    {
        $this->men_lethargy = $menLethargy;
    }

    public function getMenIrritability(): ?TripleChoice
    {
        return $this->men_irritability;
    }

    public function setMenIrritability($men_irritability): void
    {
        $this->men_irritability = $men_irritability;
    }

    public function getMenVomit(): ?TripleChoice
    {
        return $this->men_vomit;
    }

    public function setMenVomit(?TripleChoice $men_vomit): void
    {
        $this->men_vomit = $men_vomit;
    }

    public function getMenMalnutrition(): ?TripleChoice
    {
        return $this->men_malnutrition;
    }

    public function setMenMalnutrition(?TripleChoice $men_malnutrition): void
    {
        $this->men_malnutrition = $men_malnutrition;
    }

    public function setHibReceived(?VaccinationReceived $hibReceived = null): void
    {
        $this->hib_received = $hibReceived;
    }

    public function setHibDoses(?FourDoses $hibDoses = null): void
    {
        $this->hib_doses = $hibDoses;
    }

    public function setHibMostRecentDose(?DateTime $hibMostRecentDose = null): void
    {
        $this->hib_most_recent_dose = $hibMostRecentDose;
    }

    public function setPcvReceived(?VaccinationReceived $pcvReceived = null): void
    {
        $this->pcv_received = $pcvReceived;
    }

    public function setPcvDoses(?FourDoses $pcvDoses = null): void
    {
        $this->pcv_doses = $pcvDoses;
    }

    public function setPcvType(?PCVType $pcvType = null): void
    {
        $this->pcv_type = $pcvType;
    }

    public function setPcvMostRecentDose(?DateTime $pcvMostRecentDose = null): void
    {
        $this->pcv_most_recent_dose = $pcvMostRecentDose;
    }

    public function setMeningReceived(?VaccinationReceived $meningReceived = null): void
    {
        $this->mening_received = $meningReceived;
    }

    public function setMeningType(?VaccinationType $meningType = null): void
    {
        $this->mening_type = $meningType;
    }

    public function setMeningDate(?DateTime $meningMostRecentDose = null): void
    {
        $this->mening_date = $meningMostRecentDose;
    }

    public function setCsfCollected(?TripleChoice $csfCollected = null): void
    {
        $this->csf_collected = $csfCollected;
    }

    public function setCsfCollectDate(?DateTime $date = null): void
    {
        $this->csf_collect_date = $date;
    }

    public function setCsfCollectTime(?DateTime $time = null): void
    {
        $this->csf_collect_time = $time;
    }

    public function setCsfAppearance(?CSFAppearance $csfAppearance = null): void
    {
        $this->csf_appearance = $csfAppearance;
    }

    public function setBloodCollectDate(?DateTime $date = null): void
    {
        $this->blood_collect_date = $date;
    }

    public function setBloodCollected(?TripleChoice $bloodCollected = null): void
    {
        $this->blood_collected = $bloodCollected;
    }

    public function setOtherSpecimenCollected(?OtherSpecimen $otherSpecimenCollected = null): void
    {
        $this->other_specimen_collected = $otherSpecimenCollected;
    }

    public function setOtherSpecimenOther(?string $otherSpecimenOther): void
    {
        $this->other_specimen_other = $otherSpecimenOther;
    }

    public function setDischOutcome(?DischargeOutcome $dischOutcome = null): void
    {
        $this->disch_outcome = $dischOutcome;
    }

    public function setDischDx(?DischargeDiagnosis $dischDx = null): void
    {
        $this->disch_dx = $dischDx;
    }

    public function setDischDxOther(?string $dischDxOther): void
    {
        $this->disch_dx_other = $dischDxOther;
    }

    public function setDischClass(?DischargeClassification $dischClass = null): void
    {
        $this->disch_class = $dischClass;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function setResult(?CaseResult $result = null): void
    {
        $this->result = $result;
    }

    public function getDischClassOther(): ?string
    {
        return $this->disch_class_other;
    }

    public function setDischClassOther(?string $dischClassOther): void
    {
        $this->disch_class_other= $dischClassOther;
    }

    //****************************************
    //PAHO/AMR specific fields

    public function getBloodNumberOfSamples(): ?int
    {
        return $this->blood_number_of_samples;
    }

    public function setBloodNumberOfSamples(?int $blood_number_of_samples): void
    {
        $this->blood_number_of_samples = $blood_number_of_samples;
    }

    public function getBloodSecondCollectDate(): ?DateTime
    {
        return $this->blood_second_collect_date;
    }

    public function setBloodSecondCollectDate(?DateTime $blood_second_collect_date = null): void
    {
        $this->blood_second_collect_date = $blood_second_collect_date;
    }

    public function getBloodSecondCollectTime(): ?DateTime
    {
        return $this->blood_second_collect_time;
    }

    public function setBloodSecondCollectTime(?DateTime $blood_second_collect_time = null): void
    {
        $this->blood_second_collect_time = $blood_second_collect_time;
    }
}
