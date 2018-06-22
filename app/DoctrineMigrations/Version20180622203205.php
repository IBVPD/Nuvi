<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\EntityManagerInterface;
use NS\SentinelBundle\Entity;
use NS\SentinelBundle\Form\IBD\Types\Diagnosis;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180622203205 extends AbstractMigration implements ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    /** @var EntityManagerInterface */
    private $entityMgr;

    /**
     * @inheritDoc
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pneu_national_labs (rl_isol_blood_sent TINYINT(1) DEFAULT NULL, rl_isol_blood_date DATE DEFAULT NULL, rl_other_sent TINYINT(1) DEFAULT NULL, rl_other_date DATE DEFAULT NULL, type_sample_recd INT DEFAULT NULL COMMENT \'(DC2Type:SampleType)\', isolate_viable INT DEFAULT NULL COMMENT \'(DC2Type:IsolateViable)\', isolate_type INT DEFAULT NULL COMMENT \'(DC2Type:IsolateType)\', method_used_pathogen_identify INT DEFAULT NULL COMMENT \'(DC2Type:PathogenIdentifier)\', method_used_pathogen_identify_other VARCHAR(255) DEFAULT NULL, method_used_st_sg INT DEFAULT NULL COMMENT \'(DC2Type:SerotypeIdentifier)\', method_used_st_sg_other VARCHAR(255) DEFAULT NULL, Spn_lytA NUMERIC(3, 1) DEFAULT NULL, Nm_ctrA NUMERIC(3, 1) DEFAULT NULL, nm_sodC NUMERIC(3, 1) DEFAULT NULL, hi_hpd1 NUMERIC(3, 1) DEFAULT NULL, hi_hpd3 NUMERIC(3, 1) DEFAULT NULL, hi_bexA NUMERIC(3, 1) DEFAULT NULL, humanDNA_RNAseP NUMERIC(3, 1) DEFAULT NULL, final_RL_result_detection INT DEFAULT NULL COMMENT \'(DC2Type:FinalResult)\', spn_serotype INT DEFAULT NULL COMMENT \'(DC2Type:SpnSerotype)\', hi_serotype INT DEFAULT NULL COMMENT \'(DC2Type:HiSerotype)\', nm_serogroup INT DEFAULT NULL COMMENT \'(DC2Type:NmSerogroup)\', lab_id VARCHAR(255) DEFAULT NULL, dt_sample_recd DATE DEFAULT NULL, status INT NOT NULL COMMENT \'(DC2Type:CaseStatus)\', createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, comment LONGTEXT DEFAULT NULL, caseFile_id VARCHAR(255) NOT NULL, PRIMARY KEY(caseFile_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pneu_reference_labs (rrl_id VARCHAR(255) DEFAULT NULL, type_sample_recd INT DEFAULT NULL COMMENT \'(DC2Type:SampleType)\', isolate_viable INT DEFAULT NULL COMMENT \'(DC2Type:IsolateViable)\', isolate_type INT DEFAULT NULL COMMENT \'(DC2Type:IsolateType)\', method_used_pathogen_identify INT DEFAULT NULL COMMENT \'(DC2Type:PathogenIdentifier)\', method_used_pathogen_identify_other VARCHAR(255) DEFAULT NULL, method_used_st_sg INT DEFAULT NULL COMMENT \'(DC2Type:SerotypeIdentifier)\', method_used_st_sg_other VARCHAR(255) DEFAULT NULL, Spn_lytA NUMERIC(3, 1) DEFAULT NULL, Nm_ctrA NUMERIC(3, 1) DEFAULT NULL, nm_sodC NUMERIC(3, 1) DEFAULT NULL, hi_hpd1 NUMERIC(3, 1) DEFAULT NULL, hi_hpd3 NUMERIC(3, 1) DEFAULT NULL, hi_bexA NUMERIC(3, 1) DEFAULT NULL, humanDNA_RNAseP NUMERIC(3, 1) DEFAULT NULL, final_RL_result_detection INT DEFAULT NULL COMMENT \'(DC2Type:FinalResult)\', spn_serotype INT DEFAULT NULL COMMENT \'(DC2Type:SpnSerotype)\', hi_serotype INT DEFAULT NULL COMMENT \'(DC2Type:HiSerotype)\', nm_serogroup INT DEFAULT NULL COMMENT \'(DC2Type:NmSerogroup)\', lab_id VARCHAR(255) DEFAULT NULL, dt_sample_recd DATE DEFAULT NULL, status INT NOT NULL COMMENT \'(DC2Type:CaseStatus)\', createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, comment LONGTEXT DEFAULT NULL, caseFile_id VARCHAR(255) NOT NULL, INDEX IDX_855BDED69CCAEA5F (rrl_id), PRIMARY KEY(caseFile_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pneu_site_labs (blood_id VARCHAR(255) DEFAULT NULL, blood_lab_date DATE DEFAULT NULL, blood_lab_time TIME DEFAULT NULL, blood_cult_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', blood_gram_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', blood_pcr_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', blood_cult_result INT DEFAULT NULL COMMENT \'(DC2Type:CultureResult)\', blood_cult_other VARCHAR(255) DEFAULT NULL, blood_gram_stain INT DEFAULT NULL COMMENT \'(DC2Type:GramStain)\', blood_gram_result INT DEFAULT NULL COMMENT \'(DC2Type:GramStainResult)\', blood_gram_other VARCHAR(255) DEFAULT NULL, blood_pcr_result INT DEFAULT NULL COMMENT \'(DC2Type:PCRResult)\', blood_pcr_other VARCHAR(255) DEFAULT NULL, blood_second_id VARCHAR(255) DEFAULT NULL, blood_second_lab_date DATE DEFAULT NULL, blood_second_lab_time TIME DEFAULT NULL, blood_second_cult_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', blood_second_gram_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', blood_second_pcr_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', blood_second_cult_result INT DEFAULT NULL COMMENT \'(DC2Type:CultureResult)\', blood_second_cult_other VARCHAR(255) DEFAULT NULL, blood_second_gram_stain INT DEFAULT NULL COMMENT \'(DC2Type:GramStain)\', blood_second_gram_result INT DEFAULT NULL COMMENT \'(DC2Type:GramStainResult)\', blood_second_gram_other VARCHAR(255) DEFAULT NULL, blood_second_pcr_result INT DEFAULT NULL COMMENT \'(DC2Type:PCRResult)\', blood_second_pcr_other VARCHAR(255) DEFAULT NULL, other_id VARCHAR(255) DEFAULT NULL, other_lab_date DATE DEFAULT NULL, other_lab_time TIME DEFAULT NULL, other_cult_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', other_cult_result INT DEFAULT NULL COMMENT \'(DC2Type:CultureResult)\', other_cult_other VARCHAR(255) DEFAULT NULL, other_test_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', other_test_result INT DEFAULT NULL COMMENT \'(DC2Type:CultureResult)\', other_test_other VARCHAR(255) DEFAULT NULL, rl_isol_blood_sent TINYINT(1) DEFAULT NULL, rl_isol_blood_date DATE DEFAULT NULL, rl_broth_sent TINYINT(1) DEFAULT NULL, rl_broth_date DATE DEFAULT NULL, rl_other_sent TINYINT(1) DEFAULT NULL, rl_other_date DATE DEFAULT NULL, nl_isol_blood_sent TINYINT(1) DEFAULT NULL, nl_isol_blood_date DATE DEFAULT NULL, nl_broth_sent TINYINT(1) DEFAULT NULL, nl_broth_date DATE DEFAULT NULL, nl_other_sent TINYINT(1) DEFAULT NULL, nl_other_date DATE DEFAULT NULL, updatedAt DATETIME NOT NULL, status INT NOT NULL COMMENT \'(DC2Type:CaseStatus)\', pleural_fluid_culture_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', pleural_fluid_culture_result INT DEFAULT NULL COMMENT \'(DC2Type:CultureResult)\', pleural_fluid_culture_other VARCHAR(255) DEFAULT NULL, pleural_fluid_gram_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', pleural_fluid_gram_result INT DEFAULT NULL COMMENT \'(DC2Type:GramStain)\', pleural_fluid_gram_result_organism INT DEFAULT NULL COMMENT \'(DC2Type:GramStainResult)\', pleural_fluid_pcr_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', pleural_fluid_pcr_result INT DEFAULT NULL COMMENT \'(DC2Type:PCRResult)\', pleural_fluid_pcr_other VARCHAR(255) DEFAULT NULL, caseFile_id VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E2AF9244CABE0DA (caseFile_id), PRIMARY KEY(caseFile_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pneu_cases (id VARCHAR(255) NOT NULL, region_id VARCHAR(15) NOT NULL, country_id VARCHAR(15) NOT NULL, site_id VARCHAR(15) DEFAULT NULL, onset_date DATE DEFAULT NULL, adm_dx INT DEFAULT NULL COMMENT \'(DC2Type:Diagnosis)\', adm_dx_other VARCHAR(255) DEFAULT NULL, antibiotics INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', pneu_diff_breathe INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', pneu_chest_indraw INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', pneu_cough INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', pneu_cyanosis INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', pneu_stridor INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', pneu_resp_rate INT DEFAULT NULL, pneu_oxygen_saturation INT DEFAULT NULL, pneu_vomit INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', pneu_hypothermia INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', pneu_malnutrition INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', pneu_fever INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', cxr_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', cxr_result INT DEFAULT NULL COMMENT \'(DC2Type:CXRResult)\', cxr_additional_result INT DEFAULT NULL COMMENT \'(DC2Type:CXRAdditionalResult)\', hib_received INT DEFAULT NULL COMMENT \'(DC2Type:VaccinationReceived)\', hib_doses INT DEFAULT NULL COMMENT \'(DC2Type:FourDoses)\', hib_most_recent_dose DATE DEFAULT NULL, pcv_received INT DEFAULT NULL COMMENT \'(DC2Type:VaccinationReceived)\', pcv_doses INT DEFAULT NULL COMMENT \'(DC2Type:FourDoses)\', pcv_type INT DEFAULT NULL COMMENT \'(DC2Type:PCVType)\', pcv_most_recent_dose DATE DEFAULT NULL, mening_received INT DEFAULT NULL COMMENT \'(DC2Type:VaccinationReceived)\', mening_type INT DEFAULT NULL COMMENT \'(DC2Type:IBDVaccinationType)\', mening_date DATE DEFAULT NULL, blood_collected INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', blood_collect_date DATE DEFAULT NULL, blood_collect_time TIME DEFAULT NULL, other_specimen_collected INT DEFAULT NULL COMMENT \'(DC2Type:OtherSpecimen)\', other_specimen_other VARCHAR(255) DEFAULT NULL, disch_outcome INT DEFAULT NULL COMMENT \'(DC2Type:IBDDischargeOutcome)\', disch_dx INT DEFAULT NULL COMMENT \'(DC2Type:IBDDischargeDiagnosis)\', disch_dx_other VARCHAR(255) DEFAULT NULL, disch_class INT DEFAULT NULL COMMENT \'(DC2Type:IBDDischargeClassification)\', disch_class_other VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, result INT DEFAULT NULL COMMENT \'(DC2Type:CaseResult)\', blood_number_of_samples INT DEFAULT NULL, blood_second_collect_date DATE DEFAULT NULL, blood_second_collect_time TIME DEFAULT NULL, pleural_fluid_collected INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', pleural_fluid_collect_date DATE DEFAULT NULL, pleural_fluid_collect_time TIME DEFAULT NULL, lastName VARCHAR(255) DEFAULT NULL, parentalName VARCHAR(255) DEFAULT NULL, firstName VARCHAR(255) DEFAULT NULL, case_id VARCHAR(255) NOT NULL, district VARCHAR(255) DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, birthdate DATE DEFAULT NULL, dobKnown INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', age_months INT DEFAULT NULL, ageDistribution INT DEFAULT NULL, gender INT DEFAULT NULL COMMENT \'(DC2Type:Gender)\', adm_date DATE DEFAULT NULL, status INT NOT NULL COMMENT \'(DC2Type:CaseStatus)\', updatedAt DATETIME NOT NULL, createdAt DATETIME NOT NULL, hasWarning TINYINT(1) NOT NULL, INDEX IDX_80D8A83F98260155 (region_id), INDEX IDX_80D8A83FF92F3E70 (country_id), INDEX IDX_80D8A83FF6BD1646 (site_id), UNIQUE INDEX ibd_site_case_id_idx (site_id, case_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mening_cases (id VARCHAR(255) NOT NULL, region_id VARCHAR(15) NOT NULL, country_id VARCHAR(15) NOT NULL, site_id VARCHAR(15) DEFAULT NULL, onset_date DATE DEFAULT NULL, adm_dx INT DEFAULT NULL COMMENT \'(DC2Type:Diagnosis)\', adm_dx_other VARCHAR(255) DEFAULT NULL, antibiotics INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', men_seizures INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', men_fever INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', men_alt_conscious INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', men_inability_feed INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', men_neck_stiff INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', men_rash INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', men_fontanelle_bulge INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', men_lethargy INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', men_irritability INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', men_vomit INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', men_malnutrition INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', hib_received INT DEFAULT NULL COMMENT \'(DC2Type:VaccinationReceived)\', hib_doses INT DEFAULT NULL COMMENT \'(DC2Type:FourDoses)\', hib_most_recent_dose DATE DEFAULT NULL, pcv_received INT DEFAULT NULL COMMENT \'(DC2Type:VaccinationReceived)\', pcv_doses INT DEFAULT NULL COMMENT \'(DC2Type:FourDoses)\', pcv_type INT DEFAULT NULL COMMENT \'(DC2Type:PCVType)\', pcv_most_recent_dose DATE DEFAULT NULL, mening_received INT DEFAULT NULL COMMENT \'(DC2Type:VaccinationReceived)\', mening_type INT DEFAULT NULL COMMENT \'(DC2Type:IBDVaccinationType)\', mening_date DATE DEFAULT NULL, csf_collected INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', csf_collect_date DATE DEFAULT NULL, csf_collect_time TIME DEFAULT NULL, csf_appearance INT DEFAULT NULL COMMENT \'(DC2Type:CSFAppearance)\', blood_collected INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', blood_collect_date DATE DEFAULT NULL, blood_collect_time TIME DEFAULT NULL, other_specimen_collected INT DEFAULT NULL COMMENT \'(DC2Type:OtherSpecimen)\', other_specimen_other VARCHAR(255) DEFAULT NULL, disch_outcome INT DEFAULT NULL COMMENT \'(DC2Type:IBDDischargeOutcome)\', disch_dx INT DEFAULT NULL COMMENT \'(DC2Type:IBDDischargeDiagnosis)\', disch_dx_other VARCHAR(255) DEFAULT NULL, disch_class INT DEFAULT NULL COMMENT \'(DC2Type:IBDDischargeClassification)\', disch_class_other VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, result INT DEFAULT NULL COMMENT \'(DC2Type:CaseResult)\', blood_number_of_samples INT DEFAULT NULL, blood_second_collect_date DATE DEFAULT NULL, blood_second_collect_time TIME DEFAULT NULL, pleural_fluid_collected INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', pleural_fluid_collect_date DATE DEFAULT NULL, pleural_fluid_collect_time TIME DEFAULT NULL, lastName VARCHAR(255) DEFAULT NULL, parentalName VARCHAR(255) DEFAULT NULL, firstName VARCHAR(255) DEFAULT NULL, case_id VARCHAR(255) NOT NULL, district VARCHAR(255) DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, birthdate DATE DEFAULT NULL, dobKnown INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', age_months INT DEFAULT NULL, ageDistribution INT DEFAULT NULL, gender INT DEFAULT NULL COMMENT \'(DC2Type:Gender)\', adm_date DATE DEFAULT NULL, status INT NOT NULL COMMENT \'(DC2Type:CaseStatus)\', updatedAt DATETIME NOT NULL, createdAt DATETIME NOT NULL, hasWarning TINYINT(1) NOT NULL, INDEX IDX_7FD41C9998260155 (region_id), INDEX IDX_7FD41C99F92F3E70 (country_id), INDEX IDX_7FD41C99F6BD1646 (site_id), UNIQUE INDEX ibd_site_case_id_idx (site_id, case_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mening_national_labs (rl_isol_csf_sent TINYINT(1) DEFAULT NULL, rl_isol_csf_date DATE DEFAULT NULL, rl_isol_blood_sent TINYINT(1) DEFAULT NULL, rl_isol_blood_date DATE DEFAULT NULL, rl_other_sent TINYINT(1) DEFAULT NULL, rl_other_date DATE DEFAULT NULL, type_sample_recd INT DEFAULT NULL COMMENT \'(DC2Type:SampleType)\', isolate_viable INT DEFAULT NULL COMMENT \'(DC2Type:IsolateViable)\', isolate_type INT DEFAULT NULL COMMENT \'(DC2Type:IsolateType)\', method_used_pathogen_identify INT DEFAULT NULL COMMENT \'(DC2Type:PathogenIdentifier)\', method_used_pathogen_identify_other VARCHAR(255) DEFAULT NULL, method_used_st_sg INT DEFAULT NULL COMMENT \'(DC2Type:SerotypeIdentifier)\', method_used_st_sg_other VARCHAR(255) DEFAULT NULL, Spn_lytA NUMERIC(3, 1) DEFAULT NULL, Nm_ctrA NUMERIC(3, 1) DEFAULT NULL, nm_sodC NUMERIC(3, 1) DEFAULT NULL, hi_hpd1 NUMERIC(3, 1) DEFAULT NULL, hi_hpd3 NUMERIC(3, 1) DEFAULT NULL, hi_bexA NUMERIC(3, 1) DEFAULT NULL, humanDNA_RNAseP NUMERIC(3, 1) DEFAULT NULL, final_RL_result_detection INT DEFAULT NULL COMMENT \'(DC2Type:FinalResult)\', spn_serotype INT DEFAULT NULL COMMENT \'(DC2Type:SpnSerotype)\', hi_serotype INT DEFAULT NULL COMMENT \'(DC2Type:HiSerotype)\', nm_serogroup INT DEFAULT NULL COMMENT \'(DC2Type:NmSerogroup)\', lab_id VARCHAR(255) DEFAULT NULL, dt_sample_recd DATE DEFAULT NULL, status INT NOT NULL COMMENT \'(DC2Type:CaseStatus)\', createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, comment LONGTEXT DEFAULT NULL, caseFile_id VARCHAR(255) NOT NULL, PRIMARY KEY(caseFile_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mening_reference_labs (rrl_id VARCHAR(255) DEFAULT NULL, type_sample_recd INT DEFAULT NULL COMMENT \'(DC2Type:SampleType)\', isolate_viable INT DEFAULT NULL COMMENT \'(DC2Type:IsolateViable)\', isolate_type INT DEFAULT NULL COMMENT \'(DC2Type:IsolateType)\', method_used_pathogen_identify INT DEFAULT NULL COMMENT \'(DC2Type:PathogenIdentifier)\', method_used_pathogen_identify_other VARCHAR(255) DEFAULT NULL, method_used_st_sg INT DEFAULT NULL COMMENT \'(DC2Type:SerotypeIdentifier)\', method_used_st_sg_other VARCHAR(255) DEFAULT NULL, Spn_lytA NUMERIC(3, 1) DEFAULT NULL, Nm_ctrA NUMERIC(3, 1) DEFAULT NULL, nm_sodC NUMERIC(3, 1) DEFAULT NULL, hi_hpd1 NUMERIC(3, 1) DEFAULT NULL, hi_hpd3 NUMERIC(3, 1) DEFAULT NULL, hi_bexA NUMERIC(3, 1) DEFAULT NULL, humanDNA_RNAseP NUMERIC(3, 1) DEFAULT NULL, final_RL_result_detection INT DEFAULT NULL COMMENT \'(DC2Type:FinalResult)\', spn_serotype INT DEFAULT NULL COMMENT \'(DC2Type:SpnSerotype)\', hi_serotype INT DEFAULT NULL COMMENT \'(DC2Type:HiSerotype)\', nm_serogroup INT DEFAULT NULL COMMENT \'(DC2Type:NmSerogroup)\', lab_id VARCHAR(255) DEFAULT NULL, dt_sample_recd DATE DEFAULT NULL, status INT NOT NULL COMMENT \'(DC2Type:CaseStatus)\', createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, comment LONGTEXT DEFAULT NULL, caseFile_id VARCHAR(255) NOT NULL, INDEX IDX_633D3C559CCAEA5F (rrl_id), PRIMARY KEY(caseFile_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mening_site_labs (csf_id VARCHAR(255) DEFAULT NULL, csf_lab_date DATE DEFAULT NULL, csf_lab_time TIME DEFAULT NULL, csf_wcc INT DEFAULT NULL, csf_glucose INT DEFAULT NULL, csf_protein INT DEFAULT NULL, csf_cult_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', csf_gram_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', csf_binax_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', csf_lat_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', csf_pcr_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', csf_cult_result INT DEFAULT NULL COMMENT \'(DC2Type:CultureResult)\', csf_cult_other VARCHAR(255) DEFAULT NULL, csf_cult_contaminant VARCHAR(255) DEFAULT NULL, csf_gram_stain INT DEFAULT NULL COMMENT \'(DC2Type:GramStain)\', csf_gram_result INT DEFAULT NULL COMMENT \'(DC2Type:GramStainResult)\', csf_gram_other VARCHAR(255) DEFAULT NULL, csf_binax_result INT DEFAULT NULL COMMENT \'(DC2Type:BinaxResult)\', csf_lat_result INT DEFAULT NULL COMMENT \'(DC2Type:LatResult)\', csf_lat_other VARCHAR(255) DEFAULT NULL, csf_pcr_result INT DEFAULT NULL COMMENT \'(DC2Type:PCRResult)\', csf_pcr_other VARCHAR(255) DEFAULT NULL, csf_store INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', isol_store INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', blood_id VARCHAR(255) DEFAULT NULL, blood_lab_date DATE DEFAULT NULL, blood_lab_time TIME DEFAULT NULL, blood_cult_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', blood_gram_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', blood_pcr_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', blood_cult_result INT DEFAULT NULL COMMENT \'(DC2Type:CultureResult)\', blood_cult_other VARCHAR(255) DEFAULT NULL, blood_gram_stain INT DEFAULT NULL COMMENT \'(DC2Type:GramStain)\', blood_gram_result INT DEFAULT NULL COMMENT \'(DC2Type:GramStainResult)\', blood_gram_other VARCHAR(255) DEFAULT NULL, blood_pcr_result INT DEFAULT NULL COMMENT \'(DC2Type:PCRResult)\', blood_pcr_other VARCHAR(255) DEFAULT NULL, blood_second_id VARCHAR(255) DEFAULT NULL, blood_second_lab_date DATE DEFAULT NULL, blood_second_lab_time TIME DEFAULT NULL, blood_second_cult_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', blood_second_gram_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', blood_second_pcr_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', blood_second_cult_result INT DEFAULT NULL COMMENT \'(DC2Type:CultureResult)\', blood_second_cult_other VARCHAR(255) DEFAULT NULL, blood_second_gram_stain INT DEFAULT NULL COMMENT \'(DC2Type:GramStain)\', blood_second_gram_result INT DEFAULT NULL COMMENT \'(DC2Type:GramStainResult)\', blood_second_gram_other VARCHAR(255) DEFAULT NULL, blood_second_pcr_result INT DEFAULT NULL COMMENT \'(DC2Type:PCRResult)\', blood_second_pcr_other VARCHAR(255) DEFAULT NULL, other_id VARCHAR(255) DEFAULT NULL, other_lab_date DATE DEFAULT NULL, other_lab_time TIME DEFAULT NULL, other_cult_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', other_cult_result INT DEFAULT NULL COMMENT \'(DC2Type:CultureResult)\', other_cult_other VARCHAR(255) DEFAULT NULL, other_test_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', other_test_result INT DEFAULT NULL COMMENT \'(DC2Type:CultureResult)\', other_test_other VARCHAR(255) DEFAULT NULL, rl_csf_sent TINYINT(1) DEFAULT NULL, rl_csf_date DATE DEFAULT NULL, rl_isol_csf_sent TINYINT(1) DEFAULT NULL, rl_isol_csf_date DATE DEFAULT NULL, rl_isol_blood_sent TINYINT(1) DEFAULT NULL, rl_isol_blood_date DATE DEFAULT NULL, rl_broth_sent TINYINT(1) DEFAULT NULL, rl_broth_date DATE DEFAULT NULL, rl_other_sent TINYINT(1) DEFAULT NULL, rl_other_date DATE DEFAULT NULL, nl_csf_sent TINYINT(1) DEFAULT NULL, nl_csf_date DATE DEFAULT NULL, nl_isol_csf_sent TINYINT(1) DEFAULT NULL, nl_isol_csf_date DATE DEFAULT NULL, nl_isol_blood_sent TINYINT(1) DEFAULT NULL, nl_isol_blood_date DATE DEFAULT NULL, nl_broth_sent TINYINT(1) DEFAULT NULL, nl_broth_date DATE DEFAULT NULL, nl_other_sent TINYINT(1) DEFAULT NULL, nl_other_date DATE DEFAULT NULL, updatedAt DATETIME NOT NULL, status INT NOT NULL COMMENT \'(DC2Type:CaseStatus)\', caseFile_id VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C94F22D0CABE0DA (caseFile_id), PRIMARY KEY(caseFile_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pneu_national_labs ADD CONSTRAINT FK_B728AB9BCABE0DA FOREIGN KEY (caseFile_id) REFERENCES pneu_cases (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pneu_reference_labs ADD CONSTRAINT FK_855BDED6CABE0DA FOREIGN KEY (caseFile_id) REFERENCES pneu_cases (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pneu_reference_labs ADD CONSTRAINT FK_855BDED69CCAEA5F FOREIGN KEY (rrl_id) REFERENCES reference_labs (id)');
        $this->addSql('ALTER TABLE pneu_site_labs ADD CONSTRAINT FK_E2AF9244CABE0DA FOREIGN KEY (caseFile_id) REFERENCES pneu_cases (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pneu_cases ADD CONSTRAINT FK_80D8A83F98260155 FOREIGN KEY (region_id) REFERENCES regions (code)');
        $this->addSql('ALTER TABLE pneu_cases ADD CONSTRAINT FK_80D8A83FF92F3E70 FOREIGN KEY (country_id) REFERENCES countries (code)');
        $this->addSql('ALTER TABLE pneu_cases ADD CONSTRAINT FK_80D8A83FF6BD1646 FOREIGN KEY (site_id) REFERENCES sites (code)');
        $this->addSql('ALTER TABLE mening_cases ADD CONSTRAINT FK_7FD41C9998260155 FOREIGN KEY (region_id) REFERENCES regions (code)');
        $this->addSql('ALTER TABLE mening_cases ADD CONSTRAINT FK_7FD41C99F92F3E70 FOREIGN KEY (country_id) REFERENCES countries (code)');
        $this->addSql('ALTER TABLE mening_cases ADD CONSTRAINT FK_7FD41C99F6BD1646 FOREIGN KEY (site_id) REFERENCES sites (code)');
        $this->addSql('ALTER TABLE mening_national_labs ADD CONSTRAINT FK_B29629D4CABE0DA FOREIGN KEY (caseFile_id) REFERENCES mening_cases (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mening_reference_labs ADD CONSTRAINT FK_633D3C55CABE0DA FOREIGN KEY (caseFile_id) REFERENCES mening_cases (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mening_reference_labs ADD CONSTRAINT FK_633D3C559CCAEA5F FOREIGN KEY (rrl_id) REFERENCES reference_labs (id)');
        $this->addSql('ALTER TABLE mening_site_labs ADD CONSTRAINT FK_C94F22D0CABE0DA FOREIGN KEY (caseFile_id) REFERENCES mening_cases (id) ON DELETE CASCADE');
    }

    public function postUp(Schema $schema)
    {
        $this->entityMgr = $this->container->get('doctrine.orm.entity_manager');

        /** @var IBD[] $ibdCases */
        $ibdCases = $this->entityMgr->getRepository(IBD::class)->findAll();
        foreach ($ibdCases as $case) {
            switch ($case->getAdmDx()->current()) {
                case Diagnosis::SUSPECTED_MENINGITIS:
                    $this->getMeningitis($case);
                    break;
                case Diagnosis::SUSPECTED_PNEUMONIA:
                case Diagnosis::SUSPECTED_SEVERE_PNEUMONIA:
                    $this->getPneumonia($case);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pneu_national_labs DROP FOREIGN KEY FK_B728AB9BCABE0DA');
        $this->addSql('ALTER TABLE pneu_reference_labs DROP FOREIGN KEY FK_855BDED6CABE0DA');
        $this->addSql('ALTER TABLE pneu_site_labs DROP FOREIGN KEY FK_E2AF9244CABE0DA');
        $this->addSql('ALTER TABLE mening_national_labs DROP FOREIGN KEY FK_B29629D4CABE0DA');
        $this->addSql('ALTER TABLE mening_reference_labs DROP FOREIGN KEY FK_633D3C55CABE0DA');
        $this->addSql('ALTER TABLE mening_site_labs DROP FOREIGN KEY FK_C94F22D0CABE0DA');
        $this->addSql('DROP TABLE pneu_national_labs');
        $this->addSql('DROP TABLE pneu_reference_labs');
        $this->addSql('DROP TABLE pneu_site_labs');
        $this->addSql('DROP TABLE pneu_cases');
        $this->addSql('DROP TABLE mening_cases');
        $this->addSql('DROP TABLE mening_national_labs');
        $this->addSql('DROP TABLE mening_reference_labs');
        $this->addSql('DROP TABLE mening_site_labs');
    }

    /**
     * @param Entity\IBD $ibdCase
     */
    private function getMeningitis($ibdCase)
    {
        /** @var Entity\Meningitis\Meningitis $obj */
        $obj = $this->getBaseCase($ibdCase, Entity\Meningitis\Meningitis::class);

        $obj->setBloodCollectTime($ibdCase->getBloodCollectTime());
        $obj->setOnsetDate($ibdCase->getOnsetDate());
        $obj->setAdmDx($ibdCase->getAdmDx());
        $obj->setAdmDxOther($ibdCase->getAdmDxOther());
        $obj->setAntibiotics($ibdCase->getAntibiotics());
        $obj->setMenSeizures($ibdCase->getMenSeizures());
        $obj->setMenFever($ibdCase->getMenFever());
        $obj->setMenAltConscious($ibdCase->getMenAltConscious());
        $obj->setMenInabilityFeed($ibdCase->getMenInabilityFeed());
        $obj->setMenNeckStiff($ibdCase->getMenNeckStiff());
        $obj->setMenRash($ibdCase->getMenRash());
        $obj->setMenFontanelleBulge($ibdCase->getMenFontanelleBulge());
        $obj->setMenLethargy($ibdCase->getMenLethargy());
//        $obj->setMenIrritability($men_irritability);
//        $obj->setMenVomit($men_vomit);
//        $obj->setMenMalnutrition($men_malnutrition);
        $obj->setHibReceived($ibdCase->getHibReceived());
        $obj->setHibDoses($ibdCase->getHibDoses());
        $obj->setHibMostRecentDose($ibdCase->getHibMostRecentDose());
        $obj->setPcvReceived($ibdCase->getPcvReceived());
        $obj->setPcvDoses($ibdCase->getPcvDoses());
        $obj->setPcvType($ibdCase->getPcvType());
        $obj->setPcvMostRecentDose($ibdCase->getPcvMostRecentDose());
        $obj->setMeningReceived($ibdCase->getMeningReceived());
        $obj->setMeningType($ibdCase->getMeningType());
        $obj->setMeningDate($ibdCase->getMeningDate());
        $obj->setCsfCollected($ibdCase->getCsfCollected());
        $obj->setCsfCollectDate($ibdCase->getCsfCollectDate());
        $obj->setCsfCollectTime($ibdCase->getCsfCollectTime());
        $obj->setCsfAppearance($ibdCase->getCsfAppearance());
        $obj->setBloodCollectDate($ibdCase->getBloodCollectDate());
        $obj->setBloodCollected($ibdCase->getBloodCollected());
        $obj->setOtherSpecimenCollected($ibdCase->getOtherSpecimenCollected());
        $obj->setOtherSpecimenOther($ibdCase->getOtherSpecimenOther());
        $obj->setDischOutcome($ibdCase->getDischOutcome());
        $obj->setDischDx($ibdCase->getDischDx());
        $obj->setDischDxOther($ibdCase->getDischDxOther());
        $obj->setDischClass($ibdCase->getDischClass());
        $obj->setComment($ibdCase->getComment());
        $obj->setResult($ibdCase->getResult());
        $obj->setDischClassOther($ibdCase->getDischClassOther());
        $obj->setBloodNumberOfSamples($ibdCase->getBloodNumberOfSamples());
        $obj->setBloodSecondCollectDate($ibdCase->getBloodSecondCollectDate());
        $obj->setBloodSecondCollectTime($ibdCase->getBloodSecondCollectTime());
        $obj->setPleuralFluidCollected($ibdCase->getPleuralFluidCollected());
        $obj->setPleuralFluidCollectDate($ibdCase->getPleuralFluidCollectDate());
        $obj->setPleuralFluidCollectTime($ibdCase->getPleuralFluidCollectTime());

        $this->entityMgr->persist($obj);
        $this->entityMgr->flush($obj);

        if ($ibdCase->getSiteLab()) {
            /** @var Entity\IBD\SiteLab $orgLab */
            $orgLab = $ibdCase->getSiteLab();
            $lab = new Entity\Meningitis\SiteLab($obj);

            $lab->setUpdatedAt($orgLab->getUpdatedAt());
            $lab->setStatus($orgLab->getStatus());
            $lab->setOtherTestDone($orgLab->getOtherTestDone());
            $lab->setOtherTestResult($orgLab->getOtherTestResult());
            $lab->setOtherTestOther($orgLab->getOtherTestOther());
            $lab->setCsfId($orgLab->getCsfId());
            $lab->setBloodId($orgLab->getBloodId());
            $lab->setCsfLabDate($orgLab->getCsfLabDate());
            $lab->setCsfLabTime($orgLab->getCsfLabTime());
            $lab->setCsfWcc($orgLab->getCsfWcc());
            $lab->setCsfGlucose($orgLab->getCsfGlucose());
            $lab->setCsfProtein($orgLab->getCsfProtein());
            $lab->setCsfCultContaminant($orgLab->getCsfCultContaminant());
            $lab->setCsfCultDone($orgLab->getCsfCultDone());
            $lab->setCsfGramDone($orgLab->getCsfGramDone());
            $lab->setCsfBinaxDone($orgLab->getCsfBinaxDone());
            $lab->setCsfLatDone($orgLab->getCsfLatDone());
            $lab->setCsfPcrDone($orgLab->getCsfPcrDone());
            $lab->setCsfCultResult($orgLab->getCsfCultResult());
            $lab->setCsfCultOther($orgLab->getCsfCultOther());
            $lab->setCsfGramResult($orgLab->getCsfGramResult());
            $lab->setCsfGramStain($orgLab->getCsfGramStain());
            $lab->setCsfGramOther($orgLab->getCsfGramOther());
            $lab->setCsfBinaxResult($orgLab->getCsfBinaxResult());
            $lab->setCsfLatResult($orgLab->getCsfLatResult());
            $lab->setCsfLatOther($orgLab->getCsfLatOther());
            $lab->setCsfPcrResult($orgLab->getCsfPcrResult());
            $lab->setCsfPcrOther($orgLab->getCsfPcrOther());
            $lab->setCsfStore($orgLab->getCsfStore());
            $lab->setIsolStore($orgLab->getIsolStore());
            $lab->setBloodCultDone($orgLab->getBloodCultDone());
            $lab->setBloodGramDone($orgLab->getBloodGramDone());
            $lab->setBloodPcrDone($orgLab->getBloodPcrDone());
            $lab->setOtherCultDone($orgLab->getOtherCultDone());
            $lab->setBloodCultResult($orgLab->getBloodCultResult());
            $lab->setBloodCultOther($orgLab->getBloodCultOther());
            $lab->setBloodGramResult($orgLab->getBloodGramResult());
            $lab->setBloodGramStain($orgLab->getBloodGramStain());
            $lab->setBloodGramOther($orgLab->getBloodGramOther());
            $lab->setBloodPcrResult($orgLab->getBloodPcrResult());
            $lab->setBloodPcrOther($orgLab->getBloodPcrOther());
            $lab->setOtherCultResult($orgLab->getOtherCultResult());
            $lab->setOtherCultOther($orgLab->getOtherCultOther());
            $lab->setRlCsfSent($orgLab->getRlCsfSent());
            $lab->setRlIsolCsfSent($orgLab->getRlIsolCsfSent());
            $lab->setRlIsolBloodSent($orgLab->getRlIsolBloodSent());
            $lab->setRlBrothSent($orgLab->getRlBrothSent());
            $lab->setRlOtherSent($orgLab->getRlOtherSent());
            $lab->setRlOtherDate($orgLab->getRlOtherDate());
            $lab->setNlCsfSent($orgLab->getNlCsfSent());
            $lab->setNlCsfDate($orgLab->getNlCsfDate());
            $lab->setNlIsolCsfSent($orgLab->getNlIsolCsfSent());
            $lab->setNlIsolCsfDate($orgLab->getNlIsolCsfDate());
            $lab->setNlIsolBloodSent($orgLab->getNlIsolBloodSent());
            $lab->setNlIsolBloodDate($orgLab->getNlIsolBloodDate());
            $lab->setNlBrothSent($orgLab->getNlBrothSent());
            $lab->setNlBrothDate($orgLab->getNlBrothDate());
            $lab->setNlOtherSent($orgLab->getNlOtherSent());
            $lab->setNlOtherDate($orgLab->getNlOtherDate());
            $lab->setRlCsfDate($orgLab->getRlCsfDate());
            $lab->setRlIsolCsfDate($orgLab->getRlIsolCsfDate());
            $lab->setRlIsolBloodDate($orgLab->getRlIsolBloodDate());
            $lab->setRlBrothDate($orgLab->getRlBrothDate());
            $lab->setBloodLabDate($orgLab->getBloodLabDate());
            $lab->setBloodLabTime($orgLab->getBloodLabTime());
            $lab->setOtherId($orgLab->getOtherId());
            $lab->setOtherLabDate($orgLab->getOtherLabDate());
            $lab->setOtherLabTime($orgLab->getOtherLabTime());
            $lab->setBloodSecondId($orgLab->getBloodSecondId());
            $lab->setBloodSecondLabDate($orgLab->getBloodSecondLabDate());
            $lab->setBloodSecondLabTime($orgLab->getBloodSecondLabTime());
            $lab->setBloodSecondCultDone($orgLab->getBloodSecondCultDone());
            $lab->setBloodSecondGramDone($orgLab->getBloodSecondGramDone());
            $lab->setBloodSecondPcrDone($orgLab->getBloodSecondPcrDone());
            $lab->setBloodSecondCultResult($orgLab->getBloodSecondCultResult());
            $lab->setBloodSecondCultOther($orgLab->getBloodSecondCultOther());
            $lab->setBloodSecondGramStain($orgLab->getBloodSecondGramStain());
            $lab->setBloodSecondGramResult($orgLab->getBloodSecondGramResult());
            $lab->setBloodSecondGramOther($orgLab->getBloodSecondGramOther());
            $lab->setBloodSecondPcrResult($orgLab->getBloodSecondPcrResult());
            $lab->setBloodSecondPcrOther($orgLab->getBloodSecondPcrOther());

            $this->entityMgr->persist($lab);
            $this->entityMgr->flush($lab);
        }

        if ($ibdCase->getNationalLab()) {
            /** @var Entity\IBD\NationalLab $orgLab */
            $orgLab = $ibdCase->getNationalLab();
            $lab = new Entity\Meningitis\NationalLab();
            $lab->setCaseFile($obj);
            $this->updateMeningitisExternalLab($orgLab, $lab);
            $this->entityMgr->persist($lab);
            $this->entityMgr->flush($lab);
        }

        if ($ibdCase->getReferenceLab()) {
            /** @var Entity\IBD\ReferenceLab $orgLab */
            $orgLab = $ibdCase->getReferenceLab();
            $lab = new Entity\Meningitis\ReferenceLab();
            $lab->setCaseFile($obj);
            $this->updateMeningitisExternalLab($orgLab, $lab);
            $this->entityMgr->persist($lab);
            $this->entityMgr->flush($lab);
        }
    }

    /**
     * @param Entity\IBD\NationalLab|Entity\IBD\ReferenceLab $orgLab
     * @param Entity\Meningitis\NationalLab|Entity\Meningitis\ReferenceLab $lab
     */
    private function updateMeningitisExternalLab($orgLab, $lab)
    {
        if ($orgLab instanceof Entity\Meningitis\NationalLab) {
            $lab->setRlIsolCsfSent($orgLab->getRlIsolCsfSent());
            $lab->setRlIsolCsfDate($orgLab->getRlIsolCsfDate());
            $lab->setRlIsolBloodSent($orgLab->getRlIsolBloodSent());
            $lab->setRlIsolBloodDate($orgLab->getRlIsolBloodDate());
            $lab->setRlOtherSent($orgLab->getRlOtherSent());
            $lab->setRlOtherDate($orgLab->getRlOtherDate());
        }

        $lab->setSampleType($orgLab->getTypeSampleRecd());
        $lab->setIsolateViable($orgLab->getIsolateViable());
        $lab->setIsolateType($orgLab->getIsolateType());
        $lab->setPathogenIdentifierMethod($orgLab->getPathogenIdentifierMethod());
        $lab->setPathogenIdentifierOther($orgLab->getPathogenIdentifierOther());
        $lab->setSerotypeIdentifier($orgLab->getSerotypeIdentifier());
        $lab->setSerotypeIdentifierOther($orgLab->getSerotypeIdentifierOther());
        $lab->setLytA($orgLab->getLytA());
        $lab->setCtrA($orgLab->getCtrA());
        $lab->setSodC($orgLab->getSodC());
        $lab->setHpd1($orgLab->getHpd1());
        $lab->setHpd3($orgLab->getHpd3());
        $lab->setBexA($orgLab->getBexA());
        $lab->setRNaseP($orgLab->getRNaseP());
        $lab->setSpnSerotype($orgLab->getSpnSerotype());
        $lab->setHiSerotype($orgLab->getHiSerotype());
        $lab->setNmSerogroup($orgLab->getNmSerogroup());
        $lab->setTypeSampleRecd($orgLab->getTypeSampleRecd());
        $lab->setMethodUsedPathogenIdentify($orgLab->getMethodUsedPathogenIdentify());
        $lab->setMethodUsedPathogenIdentifyOther($orgLab->getMethodUsedPathogenIdentifyOther());
        $lab->setMethodUsedStSg($orgLab->getMethodUsedStSg());
        $lab->setMethodUsedStSgOther($orgLab->getMethodUsedStSgOther());
        $lab->setSpnLytA($orgLab->getSpnLytA());
        $lab->setNmCtrA($orgLab->getNmCtrA());
        $lab->setNmSodC($orgLab->getNmSodC());
        $lab->setHiHpd1($orgLab->getHiHpd1());
        $lab->setHiHpd3($orgLab->getHiHpd3());
        $lab->setHiBexA($orgLab->getHiBexA());
        $lab->setHumanDNARNAseP($orgLab->getHumanDNARNAseP());
        $lab->setFinalRLResultDetection($orgLab->getFinalRLResultDetection());
        $lab->setFinalResult($orgLab->getFinalResult());
    }

    /**
     * @param Entity\IBD $ibdCase
     */
    private function getPneumonia($ibdCase)
    {
        /** @var Entity\Pneumonia\Pneumonia $obj */
        $obj = $this->getBaseCase($ibdCase, Entity\Pneumonia\Pneumonia::class);
        $obj->setPneuOxygenSaturation($ibdCase->getPneuOxygenSaturation());
        $obj->setPneuFever($ibdCase->getPneuFever());
        $obj->setBloodCollectTime($ibdCase->getBloodCollectTime());
        $obj->setOnsetDate($ibdCase->getOnsetDate());
        $obj->setAdmDx($ibdCase->getAdmDx());
        $obj->setAdmDxOther($ibdCase->getAdmDxOther());
        $obj->setAntibiotics($ibdCase->getAntibiotics());
        $obj->setPneuDiffBreathe($ibdCase->getPneuDiffBreathe());
        $obj->setPneuChestIndraw($ibdCase->getPneuChestIndraw());
        $obj->setPneuCough($ibdCase->getPneuCough());
        $obj->setPneuCyanosis($ibdCase->getPneuCyanosis());
        $obj->setPneuStridor($ibdCase->getPneuStridor());
        $obj->setPneuRespRate($ibdCase->getPneuRespRate());
        $obj->setPneuVomit($ibdCase->getPneuVomit());
        $obj->setPneuHypothermia($ibdCase->getPneuHypothermia());
        $obj->setPneuMalnutrition($ibdCase->getPneuMalnutrition());
        $obj->setCxrDone($ibdCase->getCxrDone());
        $obj->setCxrResult($ibdCase->getCxrResult());
        $obj->setCxrAdditionalResult($ibdCase->getCxrAdditionalResult());
        $obj->setHibReceived($ibdCase->getHibReceived());
        $obj->setHibDoses($ibdCase->getHibDoses());
        $obj->setHibMostRecentDose($ibdCase->getHibMostRecentDose());
        $obj->setPcvReceived($ibdCase->getPcvReceived());
        $obj->setPcvDoses($ibdCase->getPcvDoses());
        $obj->setPcvType($ibdCase->getPcvType());
        $obj->setPcvMostRecentDose($ibdCase->getPcvMostRecentDose());
        $obj->setMeningReceived($ibdCase->getMeningReceived());
        $obj->setMeningType($ibdCase->getMeningType());
        $obj->setMeningDate($ibdCase->getMeningDate());
        $obj->setBloodCollectDate($ibdCase->getBloodCollectDate());
        $obj->setBloodCollected($ibdCase->getBloodCollected());
        $obj->setOtherSpecimenCollected($ibdCase->getOtherSpecimenCollected());
        $obj->setOtherSpecimenOther($ibdCase->getOtherSpecimenOther());
        $obj->setDischOutcome($ibdCase->getDischOutcome());
        $obj->setDischDx($ibdCase->getDischDx());
        $obj->setDischDxOther($ibdCase->getDischDxOther());
        $obj->setDischClass($ibdCase->getDischClass());
        $obj->setComment($ibdCase->getComment());
        $obj->setResult($ibdCase->getResult());
        $obj->setDischClassOther($ibdCase->getDischClassOther());
        $obj->setBloodNumberOfSamples($ibdCase->getBloodNumberOfSamples());
        $obj->setBloodSecondCollectDate($ibdCase->getBloodSecondCollectDate());
        $obj->setBloodSecondCollectTime($ibdCase->getBloodSecondCollectTime());
        $obj->setPleuralFluidCollected($ibdCase->getPleuralFluidCollected());
        $obj->setPleuralFluidCollectDate($ibdCase->getPleuralFluidCollectDate());
        $obj->setPleuralFluidCollectTime($ibdCase->getPleuralFluidCollectTime());

        $this->entityMgr->persist($obj);
        $this->entityMgr->flush($obj);

        if ($ibdCase->getSiteLab()) {
            /** @var Entity\IBD\SiteLab $orgLab */
            $orgLab = $ibdCase->getSiteLab();
            $lab = new Entity\Pneumonia\SiteLab($obj);

            $lab->setCaseFile($obj);
            $lab->setUpdatedAt($orgLab->getUpdatedAt());
            $lab->setStatus($orgLab->getStatus());
            $lab->setOtherTestDone($orgLab->getOtherTestDone());
            $lab->setOtherTestResult($orgLab->getOtherTestResult());
            $lab->setOtherTestOther($orgLab->getOtherTestOther());
            $lab->setBloodId($orgLab->getBloodId());
            $lab->setIsolStore($orgLab->getIsolStore());
            $lab->setBloodCultDone($orgLab->getBloodCultDone());
            $lab->setBloodGramDone($orgLab->getBloodGramDone());
            $lab->setBloodPcrDone($orgLab->getBloodPcrDone());
            $lab->setOtherCultDone($orgLab->getOtherCultDone());
            $lab->setBloodCultResult($orgLab->getBloodCultResult());
            $lab->setBloodCultOther($orgLab->getBloodCultOther());
            $lab->setBloodGramResult($orgLab->getBloodGramResult());
            $lab->setBloodGramStain($orgLab->getBloodGramStain());
            $lab->setBloodGramOther($orgLab->getBloodGramOther());
            $lab->setBloodPcrResult($orgLab->getBloodPcrResult());
            $lab->setBloodPcrOther($orgLab->getBloodPcrOther());
            $lab->setOtherCultResult($orgLab->getOtherCultResult());
            $lab->setOtherCultOther($orgLab->getOtherCultOther());
            $lab->setRlIsolBloodSent($orgLab->getRlIsolBloodSent());
            $lab->setRlBrothSent($orgLab->getRlBrothSent());
            $lab->setRlOtherSent($orgLab->getRlOtherSent());
            $lab->setRlOtherDate($orgLab->getRlOtherDate());
            $lab->setNlIsolBloodSent($orgLab->getNlIsolBloodSent());
            $lab->setNlIsolBloodDate($orgLab->getNlIsolBloodDate());
            $lab->setNlBrothSent($orgLab->getNlBrothSent());
            $lab->setNlBrothDate($orgLab->getNlBrothDate());
            $lab->setNlOtherSent($orgLab->getNlOtherSent());
            $lab->setNlOtherDate($orgLab->getNlOtherDate());
            $lab->setRlIsolBloodDate($orgLab->getRlIsolBloodDate());
            $lab->setRlBrothDate($orgLab->getRlBrothDate());
            $lab->setBloodLabDate($orgLab->getBloodLabDate());
            $lab->setBloodLabTime($orgLab->getBloodLabTime());
            $lab->setOtherId($orgLab->getOtherId());
            $lab->setOtherLabDate($orgLab->getOtherLabDate());
            $lab->setOtherLabTime($orgLab->getOtherLabTime());
            $lab->setBloodSecondId($orgLab->getBloodSecondId());
            $lab->setBloodSecondLabDate($orgLab->getBloodSecondLabDate());
            $lab->setBloodSecondLabTime($orgLab->getBloodSecondLabTime());
            $lab->setBloodSecondCultDone($orgLab->getBloodSecondCultDone());
            $lab->setBloodSecondGramDone($orgLab->getBloodSecondGramDone());
            $lab->setBloodSecondPcrDone($orgLab->getBloodSecondPcrDone());
            $lab->setBloodSecondCultResult($orgLab->getBloodSecondCultResult());
            $lab->setBloodSecondCultOther($orgLab->getBloodSecondCultOther());
            $lab->setBloodSecondGramStain($orgLab->getBloodSecondGramStain());
            $lab->setBloodSecondGramResult($orgLab->getBloodSecondGramResult());
            $lab->setBloodSecondGramOther($orgLab->getBloodSecondGramOther());
            $lab->setBloodSecondPcrResult($orgLab->getBloodSecondPcrResult());
            $lab->setBloodSecondPcrOther($orgLab->getBloodSecondPcrOther());
            $lab->setPleuralFluidCultureDone($orgLab->getPleuralFluidCultureDone());
            $lab->setPleuralFluidCultureResult($orgLab->getPleuralFluidCultureResult());
            $lab->setPleuralFluidCultureOther($orgLab->getPleuralFluidCultureOther());
            $lab->setPleuralFluidGramDone($orgLab->getPleuralFluidGramDone());
            $lab->setPleuralFluidGramResult($orgLab->getPleuralFluidGramResult());
            $lab->setPleuralFluidGramResultOrganism($orgLab->getPleuralFluidGramResultOrganism());
            $lab->setPleuralFluidPcrDone($orgLab->getPleuralFluidPcrDone());
            $lab->setPleuralFluidPcrResult($orgLab->getPleuralFluidPcrResult());
            $lab->setPleuralFluidPcrOther($orgLab->getPleuralFluidPcrOther());

            $this->entityMgr->persist($lab);
            $this->entityMgr->flush($lab);
        }

        if ($ibdCase->getNationalLab()) {
            /** @var Entity\IBD\NationalLab $orgLab */
            $orgLab = $ibdCase->getNationalLab();
            $lab = new Entity\Pneumonia\NationalLab();
            $this->updatePneumoniaExternalLab($orgLab, $lab);
            $this->entityMgr->persist($lab);
            $this->entityMgr->flush($lab);
        }

        if ($ibdCase->getReferenceLab()) {
            /** @var Entity\IBD\ReferenceLab $orgLab */
            $orgLab = $ibdCase->getReferenceLab();
            $lab = new Entity\Pneumonia\ReferenceLab();
            $this->updatePneumoniaExternalLab($orgLab, $lab);
            $this->entityMgr->persist($lab);
            $this->entityMgr->flush($lab);
        }
    }

    /**
     * @param Entity\IBD\NationalLab|Entity\IBD\ReferenceLab $orgLab
     * @param Entity\Pneumonia\NationalLab|Entity\Pneumonia\ReferenceLab $lab
     */
    private function updatePneumoniaExternalLab($orgLab, $lab)
    {
        if ($orgLab instanceof Entity\Pneumonia\NationalLab) {
            $lab->setRlIsolBloodSent($orgLab->isRlIsolBloodSent());
            $lab->setRlIsolBloodDate($orgLab->getRlIsolBloodDate());
            $lab->setRlOtherSent($orgLab->isRlOtherSent());
            $lab->setRlOtherDate($orgLab->getRlOtherDate());
        }

        $lab->setSampleType($orgLab->getTypeSampleRecd());
        $lab->setIsolateViable($orgLab->getIsolateViable());
        $lab->setIsolateType($orgLab->getIsolateType());
        $lab->setPathogenIdentifierMethod($orgLab->getPathogenIdentifierMethod());
        $lab->setPathogenIdentifierOther($orgLab->getPathogenIdentifierOther());
        $lab->setSerotypeIdentifier($orgLab->getSerotypeIdentifier());
        $lab->setSerotypeIdentifierOther($orgLab->getSerotypeIdentifierOther());
        $lab->setLytA($orgLab->getLytA());
        $lab->setCtrA($orgLab->getCtrA());
        $lab->setSodC($orgLab->getSodC());
        $lab->setHpd1($orgLab->getHpd1());
        $lab->setHpd3($orgLab->getHpd3());
        $lab->setBexA($orgLab->getBexA());
        $lab->setRNaseP($orgLab->getRNaseP());
        $lab->setSpnSerotype($orgLab->getSpnSerotype());
        $lab->setHiSerotype($orgLab->getHiSerotype());
        $lab->setNmSerogroup($orgLab->getNmSerogroup());
        $lab->setTypeSampleRecd($orgLab->getTypeSampleRecd());
        $lab->setMethodUsedPathogenIdentify($orgLab->getMethodUsedPathogenIdentify());
        $lab->setMethodUsedPathogenIdentifyOther($orgLab->getMethodUsedPathogenIdentifyOther());
        $lab->setMethodUsedStSg($orgLab->getMethodUsedStSg());
        $lab->setMethodUsedStSgOther($orgLab->getMethodUsedStSgOther());
        $lab->setSpnLytA($orgLab->getSpnLytA());
        $lab->setNmCtrA($orgLab->getNmCtrA());
        $lab->setNmSodC($orgLab->getNmSodC());
        $lab->setHiHpd1($orgLab->getHiHpd1());
        $lab->setHiHpd3($orgLab->getHiHpd3());
        $lab->setHiBexA($orgLab->getHiBexA());
        $lab->setHumanDNARNAseP($orgLab->getHumanDNARNAseP());
        $lab->setFinalRLResultDetection($orgLab->getFinalRLResultDetection());
        $lab->setFinalResult($orgLab->getFinalResult());
    }

    /**
     * @param Entity\IBD $ibdCase
     * @param $newCaseClass
     */
    private function getBaseCase($ibdCase, $newCaseClass)
    {
        /** @var Entity\BaseCase $obj */
        $obj = new $newCaseClass();
        $obj->setId($ibdCase->getId());
        if ($ibdCase->getSite()) {
            $obj->setSite($ibdCase->getSite());
        } else {
            $obj->setCountry($ibdCase->getCountry());
        }
        $obj->setStatus($ibdCase->getStatus());
//    $obj->setReferenceLab(BaseExternalLab $lab)
//    $obj->setNationalLab(BaseExternalLab $lab)
//    $obj->setSiteLab($siteLab)
        $obj->setUpdatedAt($ibdCase->getUpdatedAt());
        $obj->setCreatedAt($ibdCase->getCreatedAt());
        $obj->setDobKnown($ibdCase->getDobKnown());
        $obj->setDobYearMonths($ibdCase->getDobYearMonths());
        $obj->setAdmDate($ibdCase->getAdmDate());
        $obj->setBirthdate($ibdCase->getBirthdate());
        $obj->setDob($ibdCase->getDob());
        $obj->setCaseId($ibdCase->getCaseId());
//    $obj->setAge($age);
        $obj->setAgeMonths($ibdCase->getAgeMonths());
        $obj->setGender($ibdCase->getGender());
        $obj->setParentalName($ibdCase->getParentalName());
        $obj->setLastName($ibdCase->getLastName());
        $obj->setFirstName($ibdCase->getFirstName());
//    $obj->setAgeDistribution($ageDistribution);
        $obj->setDistrict($ibdCase->getDistrict());
//    $obj->setState($state);
//    $obj->setWarning($warning);
    }
}
