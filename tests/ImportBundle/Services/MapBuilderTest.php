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

/**
 * Description of MapBuilderTest
 *
 * @author gnat
 */
class MapBuilderTest extends WebTestCase
{
    public function testProcessFile()
    {
        $ibdClass   = 'NS\SentinelBundle\Entity\IBD';
        $meta       = $this->getContainer()->get('doctrine.orm.entity_manager')->getClassMetadata($ibdClass);
        $siteMeta   = $this->getContainer()->get('doctrine.orm.entity_manager')->getClassMetadata('NS\SentinelBundle\Entity\IBD\SiteLab');
        $externMeta = $this->getContainer()->get('doctrine.orm.entity_manager')->getClassMetadata('NS\SentinelBundle\Entity\IBD\NationalLab');
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
            'ns.sentinel.converter.spnSerotype'                   => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\SpnSerotype','Both'),
            'ns.sentinel.converter.serotypeIdentifier'            => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\SerotypeIdentifier','Both'),
            'ns.sentinel.converter.VaccinationReceived'           => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\VaccinationReceived','Both'),
            'ns.sentinel.converter.threeDoses'                    => new DosesConverter('NS\SentinelBundle\Form\Types\ThreeDoses','Both'),
            'ns.sentinel.converter.fourDoses'                     => new DosesConverter('NS\SentinelBundle\Form\Types\FourDoses','Both'),
            'ns.sentinel.converter.rotavirusDischargeOutcome'     => new ArrayChoiceConverter('NS\SentinelBundle\Form\RotaVirus\Types\DischargeOutcome','Both'),
            'ns.sentinel.converter.rehydration'                   => new ArrayChoiceConverter('NS\SentinelBundle\Form\RotaVirus\Types\Rehydration','Both'),
            'ns.sentinel.converter.pathogenIdentifier'            => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\PathogenIdentifier','Both'),
            'ns.sentinel.converter.pcvType'                       => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\PCVType','Both'),
            'ns.sentinel.converter.pcrResult'                     => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\PCRResult','Both'),
            'ns.sentinel.converter.otherSpecimen'                 => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\OtherSpecimen','Both'),
            'ns.sentinel.converter.nmSerogroup'                   => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\NmSerogroup','Both'),
            'ns.sentinel.converter.MeningVaccinationType'         => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\VaccinationType','Both'),
            'ns.sentinel.converter.latResult'                     => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\LatResult','Both'),
            'ns.sentinel.converter.isolateType'                   => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\IsolateType','Both'),
            'ns.sentinel.converter.ibdCaseResult'                 => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\CaseResult','Both'),
            'ns.sentinel.converter.hiSerotype'                    => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\HiSerotype','Both'),
            'ns.sentinel.converter.gramStainResult'               => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\GramStainResult','Both'),
            'ns.sentinel.converter.gramStain'                     => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\GramStain','Both'),
            'ns.sentinel.converter.genotypeResultP'               => new ArrayChoiceConverter('NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP','Both'),
            'ns.sentinel.converter.genotypeResultG'               => new ArrayChoiceConverter('NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG','Both'),
            'ns.sentinel.converter.elisaKit'                      => new ArrayChoiceConverter('NS\SentinelBundle\Form\RotaVirus\Types\ElisaKit','Both'),
            'ns.sentinel.converter.elisaResult'                   => new ArrayChoiceConverter('NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult','Both'),
            'ns.sentinel.converter.dischargeOutcome'              => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\DischargeOutcome','Both'),
            'ns.sentinel.converter.dischargeClassification'       => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\DischargeClassification','Both'),
            'ns.sentinel.converter.diagnosis'                     => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\Diagnosis','Both'),
            'ns.sentinel.converter.dehydration'                   => new ArrayChoiceConverter('NS\SentinelBundle\Form\RotaVirus\Types\Dehydration','Both'),
            'ns.sentinel.converter.cultureResult'                 => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\CultureResult','Both'),
            'ns.sentinel.converter.caseStatus'                    => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\CaseStatus','Both'),
            'ns.sentinel.converter.cxrResult'                     => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\CXRResult','Both'),
            'ns.sentinel.converter.cxrAdditionalResult'           => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\CXRAdditionalResult','Both'),
            'ns.sentinel.converter.binaxResult'                   => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\BinaxResult','Both'),
            'ns.sentinel.converter.csfAppearance'                 => new ArrayChoiceConverter('NS\SentinelBundle\Form\IBD\Types\CSFAppearance','Both'),
            'ns.sentinel.converter.gender'                        => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\Gender','Both'),
            'ns.sentinel.converter.triple_choice'                 => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\TripleChoice','Both'),
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
        $mockReader = $this->createMock('NS\ImportBundle\Reader\CsvReader');

        $mockReader->expects($this->once())
            ->method('setHeaderRowNumber')
            ->with(0);
        $mockReader->expects($this->once())
            ->method('getColumnHeaders')
            ->willReturn(['columnOne', 'columnTwo']);

        $mockReaderFactory = $this->createMock('NS\ImportBundle\Reader\ReaderFactory');
        $mockReaderFactory->expects($this->once())
            ->method('getReader')
            ->willReturn($mockReader);

        $ibdClass   = 'NS\SentinelBundle\Entity\IBD';
        $meta       = $this->getContainer()->get('doctrine.orm.entity_manager')->getClassMetadata($ibdClass);
        $siteMeta   = $this->getContainer()->get('doctrine.orm.entity_manager')->getClassMetadata('NS\SentinelBundle\Entity\IBD\SiteLab');
        $externMeta = $this->getContainer()->get('doctrine.orm.entity_manager')->getClassMetadata('NS\SentinelBundle\Entity\IBD\NationalLab');

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
