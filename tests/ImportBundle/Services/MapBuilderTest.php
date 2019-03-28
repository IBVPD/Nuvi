<?php

namespace NS\ImportBundle\Tests\Services;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use NS\ImportBundle\Converter\Registry;
use NS\ImportBundle\Entity\Column;
use NS\ImportBundle\Entity\Map;
use NS\ImportBundle\Reader\ReaderFactory;
use NS\ImportBundle\Services\MapBuilder;
use NS\SentinelBundle\Converter\ArrayChoiceConverter;
use NS\SentinelBundle\Converter\DosesConverter;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use NS\SentinelBundle\Entity\IBD\NationalLab;
use NS\SentinelBundle\Entity\IBD\SiteLab;
use NS\SentinelBundle\Entity\IBD;
use NS\ImportBundle\Reader\CsvReader;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\IBD\Types\CSFAppearance;
use NS\SentinelBundle\Form\IBD\Types\BinaxResult;
use NS\SentinelBundle\Form\IBD\Types\CXRAdditionalResult;
use NS\SentinelBundle\Form\IBD\Types\CXRResult;
use NS\SentinelBundle\Form\Types\CaseStatus;
use NS\SentinelBundle\Form\IBD\Types\CultureResult;
use NS\SentinelBundle\Form\RotaVirus\Types\Dehydration;
use NS\SentinelBundle\Form\IBD\Types\Diagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeClassification;
use NS\SentinelBundle\Form\IBD\Types\DischargeOutcome;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaKit;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP;
use NS\SentinelBundle\Form\IBD\Types\GramStain;
use NS\SentinelBundle\Form\IBD\Types\GramStainResult;
use NS\SentinelBundle\Form\IBD\Types\HiSerotype;
use NS\SentinelBundle\Form\IBD\Types\CaseResult;
use NS\SentinelBundle\Form\IBD\Types\IsolateType;
use NS\SentinelBundle\Form\IBD\Types\LatResult;
use NS\SentinelBundle\Form\IBD\Types\VaccinationType;
use NS\SentinelBundle\Form\IBD\Types\NmSerogroup;
use NS\SentinelBundle\Form\IBD\Types\OtherSpecimen;
use NS\SentinelBundle\Form\IBD\Types\PCRResult;
use NS\SentinelBundle\Form\IBD\Types\PCVType;
use NS\SentinelBundle\Form\IBD\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\RotaVirus\Types\Rehydration;
use NS\SentinelBundle\Form\Types\FourDoses;
use NS\SentinelBundle\Form\Types\ThreeDoses;
use NS\SentinelBundle\Form\Types\VaccinationReceived;
use NS\SentinelBundle\Form\IBD\Types\SerotypeIdentifier;
use NS\SentinelBundle\Form\IBD\Types\SpnSerotype;

/**
 * Description of MapBuilderTest
 *
 * @author gnat
 */
class MapBuilderTest extends WebTestCase
{
    public function testProcessFile()
    {
        $ibdClass   = IBD::class;
        $meta       = $this->getContainer()->get('doctrine.orm.entity_manager')->getClassMetadata($ibdClass);
        $siteMeta   = $this->getContainer()->get('doctrine.orm.entity_manager')->getClassMetadata(SiteLab::class);
        $externMeta = $this->getContainer()->get('doctrine.orm.entity_manager')->getClassMetadata(NationalLab::class);
        $readerFact = new ReaderFactory();

        $mapBuilder = new MapBuilder($readerFact);
        $mapBuilder->setConverterRegistry($this->getConverterRegistry());
        $mapBuilder->setMetaData($meta);
        $mapBuilder->setSiteMetaData($siteMeta);
        $mapBuilder->setNlMetaData($externMeta);

        $map  = new Map();
        $file = new UploadedFile(__DIR__ . '/../Fixtures/IBD.csv', 'IBD.csv');

        $map->setClass($ibdClass);
        $map->setName('Test File');
        $map->setVersion('1.0');
        $map->setFile($file);

        $mapBuilder->process($map);
        $columns    = $map->getColumns();
        $this->assertGreaterThan(0, $columns->count());
        $this->assertTrue($columns[0]->isIgnored());
        $this->assertTrue($columns[1]->isIgnored());
        $this->assertInstanceOf(Column::class,$columns[3]);
        $this->assertNotNull($columns[3]->getConverter(), sprintf('Name: %s, Ignored: %s, Mapper: %s, Converter: %s',$columns[3]->getName(),$columns[3]->isIgnored()?'Yes':'No',$columns[3]->getMapper(),$columns[3]->getConverter()));
        $this->assertEquals('ns.sentinel.converter.gender', $columns[3]->getConverter());

        $headers = $map->getColumnHeaders();
        foreach ($headers as $index => $name) {
            $this->assertEquals($columns[$index]->getName(), $name);
        }
    }

    private function getConverterRegistry()
    {
        $converters = [
            'ns.sentinel.converter.spnSerotype'                   => new ArrayChoiceConverter(SpnSerotype::class,'Both'),
            'ns.sentinel.converter.serotypeIdentifier'            => new ArrayChoiceConverter(SerotypeIdentifier::class,'Both'),
            'ns.sentinel.converter.VaccinationReceived'           => new ArrayChoiceConverter(VaccinationReceived::class,'Both'),
            'ns.sentinel.converter.threeDoses'                    => new DosesConverter(ThreeDoses::class,'Both'),
            'ns.sentinel.converter.fourDoses'                     => new DosesConverter(FourDoses::class,'Both'),
            'ns.sentinel.converter.rotavirusDischargeOutcome'     => new ArrayChoiceConverter(\NS\SentinelBundle\Form\RotaVirus\Types\DischargeOutcome::class,'Both'),
            'ns.sentinel.converter.rehydration'                   => new ArrayChoiceConverter(Rehydration::class,'Both'),
            'ns.sentinel.converter.pathogenIdentifier'            => new ArrayChoiceConverter(PathogenIdentifier::class,'Both'),
            'ns.sentinel.converter.pcvType'                       => new ArrayChoiceConverter(PCVType::class,'Both'),
            'ns.sentinel.converter.pcrResult'                     => new ArrayChoiceConverter(PCRResult::class,'Both'),
            'ns.sentinel.converter.otherSpecimen'                 => new ArrayChoiceConverter(OtherSpecimen::class,'Both'),
            'ns.sentinel.converter.nmSerogroup'                   => new ArrayChoiceConverter(NmSerogroup::class,'Both'),
            'ns.sentinel.converter.MeningVaccinationType'         => new ArrayChoiceConverter(VaccinationType::class,'Both'),
            'ns.sentinel.converter.latResult'                     => new ArrayChoiceConverter(LatResult::class,'Both'),
            'ns.sentinel.converter.isolateType'                   => new ArrayChoiceConverter(IsolateType::class,'Both'),
            'ns.sentinel.converter.ibdCaseResult'                 => new ArrayChoiceConverter(CaseResult::class,'Both'),
            'ns.sentinel.converter.hiSerotype'                    => new ArrayChoiceConverter(HiSerotype::class,'Both'),
            'ns.sentinel.converter.gramStainResult'               => new ArrayChoiceConverter(GramStainResult::class,'Both'),
            'ns.sentinel.converter.gramStain'                     => new ArrayChoiceConverter(GramStain::class,'Both'),
            'ns.sentinel.converter.genotypeResultP'               => new ArrayChoiceConverter(GenotypeResultP::class,'Both'),
            'ns.sentinel.converter.genotypeResultG'               => new ArrayChoiceConverter(GenotypeResultG::class,'Both'),
            'ns.sentinel.converter.elisaKit'                      => new ArrayChoiceConverter(ElisaKit::class,'Both'),
            'ns.sentinel.converter.elisaResult'                   => new ArrayChoiceConverter(ElisaResult::class,'Both'),
            'ns.sentinel.converter.dischargeOutcome'              => new ArrayChoiceConverter(DischargeOutcome::class,'Both'),
            'ns.sentinel.converter.dischargeClassification'       => new ArrayChoiceConverter(DischargeClassification::class,'Both'),
            'ns.sentinel.converter.diagnosis'                     => new ArrayChoiceConverter(Diagnosis::class,'Both'),
            'ns.sentinel.converter.dehydration'                   => new ArrayChoiceConverter(Dehydration::class,'Both'),
            'ns.sentinel.converter.cultureResult'                 => new ArrayChoiceConverter(CultureResult::class,'Both'),
            'ns.sentinel.converter.caseStatus'                    => new ArrayChoiceConverter(CaseStatus::class,'Both'),
            'ns.sentinel.converter.cxrResult'                     => new ArrayChoiceConverter(CXRResult::class,'Both'),
            'ns.sentinel.converter.cxrAdditionalResult'           => new ArrayChoiceConverter(CXRAdditionalResult::class,'Both'),
            'ns.sentinel.converter.binaxResult'                   => new ArrayChoiceConverter(BinaxResult::class,'Both'),
            'ns.sentinel.converter.csfAppearance'                 => new ArrayChoiceConverter(CSFAppearance::class,'Both'),
            'ns.sentinel.converter.gender'                        => new ArrayChoiceConverter(Gender::class,'Both'),
            'ns.sentinel.converter.triple_choice'                 => new ArrayChoiceConverter(TripleChoice::class,'Both'),
        ];

        $converterRegistry = new Registry();
        foreach ($converters as $id => $converter) {
            $converterRegistry->addConverter(strtolower($id), $converter);
        }

        return $converterRegistry;
    }

    /**
     *
     * @param string $expected
     * @param string $input
     * @dataProvider camelCaseProvider
     */
    public function testCamelCase($expected, $input)
    {
        $mapBuilder = new MapBuilder(new ReaderFactory());
        $this->assertEquals($expected, $mapBuilder->camelCase($input));
    }

    public function camelCaseProvider()
    {
        return [
            ['expected' => 'caseId', 'input' => 'case_ID'],
            ['expected' => 'menSeizures', 'input' => 'men_seizures'],
            ['expected' => 'menInabilityFeed', 'input' => 'men_inability_feed'],
            ['expected' => 'menNeckStiff', 'input' => 'men_neck_stiff'],
            ['expected' => 'csfId', 'input' => 'CSF_ID'],
            ['expected' => 'csfLabDate', 'input' => 'CSF_lab_date'],
            ['expected' => 'csfWcc', 'input' => 'CSF_WCC'],
            ['expected' => 'caseId', 'input' => 'cAse!ID'],
            ['expected' => 'menSeizures', 'input' => 'men # seizures'],
            ['expected' => 'menInabilityFeed', 'input' => 'men  inability  feed'],
            ['expected' => 'menNeckStiff', 'input' => 'men  neck  stiff'],
            ['expected' => 'csfId', 'input' => 'CSF  ID'],
            ['expected' => 'csfLabDate', 'input' => 'CSF  lab  date'],
            ['expected' => 'csfWcc', 'input' => 'CSF  WCC'],
        ];
    }

    public function testSetHeaderIsCalled()
    {
        $mockReader = $this->createMock(CsvReader::class);

        $mockReader->expects($this->once())
            ->method('setHeaderRowNumber')
            ->with(0);
        $mockReader->expects($this->once())
            ->method('getColumnHeaders')
            ->willReturn(['columnOne', 'columnTwo']);

        $mockReaderFactory = $this->createMock(ReaderFactory::class);
        $mockReaderFactory->expects($this->once())
            ->method('getReader')
            ->willReturn($mockReader);

        $ibdClass   = IBD::class;
        $meta       = $this->getContainer()->get('doctrine.orm.entity_manager')->getClassMetadata($ibdClass);
        $siteMeta   = $this->getContainer()->get('doctrine.orm.entity_manager')->getClassMetadata(SiteLab::class);
        $externMeta = $this->getContainer()->get('doctrine.orm.entity_manager')->getClassMetadata(NationalLab::class);

        $mapBuilder = new MapBuilder($mockReaderFactory);
        $mapBuilder->setConverterRegistry($this->getConverterRegistry());
        $mapBuilder->setMetaData($meta);
        $mapBuilder->setSiteMetaData($siteMeta);
        $mapBuilder->setNlMetaData($externMeta);

        $map  = new Map();
        $file = new UploadedFile(__DIR__ . '/../Fixtures/IBD.csv', 'IBD.csv');

        $map->setClass($ibdClass);
        $map->setName('Test File');
        $map->setVersion('1.0');
        $map->setFile($file);

        $mapBuilder->process($map);
    }
}
