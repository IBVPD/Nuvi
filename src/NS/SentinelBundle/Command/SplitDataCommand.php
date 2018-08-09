<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 27/06/18
 * Time: 4:01 PM
 */

namespace NS\SentinelBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use NS\SentinelBundle\Entity;
use NS\SentinelBundle\Form\IBD\Types\Diagnosis;

use NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SplitDataCommand extends ContainerAwareCommand
{
    /** @var EntityManagerInterface */
    private $entityMgr;

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('nssentinel:split-ibd')
            ->setDefinition([
                new InputArgument('start-position',InputArgument::REQUIRED,'Start record index'),
                new InputOption('batch-size', 'b', InputOption::VALUE_REQUIRED,'Batch size', 500),
                new InputOption('case', 'c', InputOption::VALUE_REQUIRED,'Case id', null)
            ]);
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->entityMgr = $this->getContainer()->get('doctrine.orm.entity_manager');
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->entityMgr->getRepository(Entity\IBD::class)->createQueryBuilder('i');
        if ($input->getOption('case')) {
            $queryBuilder->where('i.case_id = :caseId')->setParameters(['caseId'=>$input->getOption('case')]);
        } else {
            $queryBuilder
                ->setMaxResults($input->getOption('batch-size'))
                ->setFirstResult($input->getArgument('start-position'))
                ->orderBy('i.adm_date');
        }

        $cases = $queryBuilder->getQuery()->getResult();

        $output->writeln("Retrieved ".count($cases)." cases");

        $processed = 0;
        /** @var Entity\IBD $case */
        foreach ($cases as $case) {
//            $output->writeln('Handling case '.$case->getId().' cId: '.$case->getCaseId());
            if ($case->getDischDx()->equal(DischargeDiagnosis::MULTIPLE)) {
                $this->getMeningitis($case);
                $this->getPneumonia($case);
                $processed++;
                continue;
            }

            switch ($case->getAdmDx()->getValue()) {
                case Diagnosis::SUSPECTED_MENINGITIS:
                    $processed++;
                    $this->getMeningitis($case);
                    break;
                case Diagnosis::SUSPECTED_PNEUMONIA:
                case Diagnosis::SUSPECTED_SEVERE_PNEUMONIA:
                    $processed++;
                    $this->getPneumonia($case);
                    break;
                default:
                    $explodedId = explode('-',$case->getCaseId());
                    if (count($explodedId) > 1 && strlen($explodedId[1]) === 1) {
                        switch($explodedId[1]) {
                            case 'M':
                                $processed++;
                                $this->getMeningitis($case);
                                break;
                            case 'N':
                                $processed++;
                                $this->getPneumonia($case);
                                break;
                            default:
                                $output->writeln(sprintf('1 - Unable to determine type for id:%s, adm: %s',$case->getCaseId(),(string)$case->getAdmDx()));
                        }
                    }
                    break;
            }
        }

        $output->writeln("Processed $processed cases");
    }

    /**
     * @param Entity\IBD $ibdCase
     */
    private function getMeningitis(Entity\IBD $ibdCase)
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
    private function getPneumonia(Entity\IBD $ibdCase)
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
            $lab->setCaseFile($obj);
            $this->updatePneumoniaExternalLab($orgLab, $lab);
            $this->entityMgr->persist($lab);
            $this->entityMgr->flush($lab);
        }

        if ($ibdCase->getReferenceLab()) {
            /** @var Entity\IBD\ReferenceLab $orgLab */
            $orgLab = $ibdCase->getReferenceLab();
            $lab = new Entity\Pneumonia\ReferenceLab();
            $lab->setCaseFile($obj);
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
        return $obj;
    }
}
