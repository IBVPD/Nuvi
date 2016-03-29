<?php

namespace NS\ImportBundle\Tests\Services;

use \Liip\FunctionalTestBundle\Test\WebTestCase;
use \NS\ImportBundle\Converter\Registry;
use \NS\ImportBundle\Entity\Map;
use NS\ImportBundle\Reader\ReaderFactory;
use \NS\ImportBundle\Services\MapBuilder;
use \NS\SentinelBundle\Converter\ArrayChoiceConverter;
use \NS\SentinelBundle\Converter\DosesConverter;
use \Symfony\Component\HttpFoundation\File\UploadedFile;

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
        $this->assertEquals($mapBuilder->camelCase($columns[2]->getName()), $columns[2]->getMapper());
        $this->assertEquals($mapBuilder->camelCase($columns[3]->getName()), $columns[3]->getMapper());
        $this->assertNotNull($columns[3]->getConverter());
        $this->assertEquals('ns.sentinel.converter.gender', $columns[3]->getConverter());

        $headers = $map->getColumnHeaders();
        foreach ($headers as $index => $name) {
            $this->assertEquals($columns[$index]->getName(), $name);
        }
    }

    private function getConverterRegistry()
    {
        $converters = array(
            'ns.sentinel.converter.spnSerotype'                   => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\SpnSerotype'),
            'ns.sentinel.converter.serotypeIdentifier'            => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\SerotypeIdentifier'),
            'ns.sentinel.converter.rotavirusVaccinationReceived'  => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\RotavirusVaccinationReceived'),
            'ns.sentinel.converter.VaccinationReceived'           => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\VaccinationReceived'),
            'ns.sentinel.converter.threeDoses'                    => new DosesConverter('NS\SentinelBundle\Form\Types\ThreeDoses'),
            'ns.sentinel.converter.fourDoses'                     => new DosesConverter('NS\SentinelBundle\Form\Types\FourDoses'),
            'ns.sentinel.converter.rotavirusDischargeOutcome'     => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\RotavirusDischargeOutcome'),
            'ns.sentinel.converter.rehydration'                   => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\Rehydration'),
            'ns.sentinel.converter.pathogenIdentifier'            => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\PathogenIdentifier'),
            'ns.sentinel.converter.pcvType'                       => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\PCVType'),
            'ns.sentinel.converter.pcrResult'                     => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\PCRResult'),
            'ns.sentinel.converter.otherSpecimen'                 => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\OtherSpecimen'),
            'ns.sentinel.converter.nmSerogroup'                   => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\NmSerogroup'),
            'ns.sentinel.converter.MeningVaccinationType'         => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\MeningitisVaccinationType'),
            'ns.sentinel.converter.meningitisVaccinationReceived' => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\MeningitisVaccinationReceived'),
            'ns.sentinel.converter.latResult'                     => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\LatResult'),
            'ns.sentinel.converter.isolateType'                   => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\IsolateType'),
            'ns.sentinel.converter.ibdCaseResult'                 => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\IBDCaseResult'),
            'ns.sentinel.converter.hiSerotype'                    => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\HiSerotype'),
            'ns.sentinel.converter.gramStainResult'               => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\GramStainResult'),
            'ns.sentinel.converter.gramStain'                     => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\GramStain'),
            'ns.sentinel.converter.genotypeResultP'               => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\GenotypeResultP'),
            'ns.sentinel.converter.genotypeResultG'               => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\GenotypeResultG'),
            'ns.sentinel.converter.elisaKit'                      => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\ElisaKit'),
            'ns.sentinel.converter.elisaResult'                   => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\ElisaResult'),
            'ns.sentinel.converter.dischargeOutcome'              => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\DischargeOutcome'),
            'ns.sentinel.converter.dischargeClassification'       => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\DischargeClassification'),
            'ns.sentinel.converter.diagnosis'                     => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\Diagnosis'),
            'ns.sentinel.converter.dehydration'                   => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\Dehydration'),
            'ns.sentinel.converter.cultureResult'                 => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\CultureResult'),
            'ns.sentinel.converter.caseStatus'                    => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\CaseStatus'),
            'ns.sentinel.converter.cxrResult'                     => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\CXRResult'),
            'ns.sentinel.converter.cxrAdditionalResult'           => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\CXRAdditionalResult'),
            'ns.sentinel.converter.binaxResult'                   => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\BinaxResult'),
            'ns.sentinel.converter.csfAppearance'                 => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\CSFAppearance'),
            'ns.sentinel.converter.gender'                        => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\Gender'),
            'ns.sentinel.converter.triple_choice'                 => new ArrayChoiceConverter('NS\SentinelBundle\Form\Types\TripleChoice'),
        );

        $converterRegistry = new Registry();
        foreach ($converters as $id => $converter) {
            $converterRegistry->addConverter($id, $converter);
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
        return array(
            array('expected' => 'caseId', 'input' => 'case_ID'),
            array('expected' => 'menSeizures', 'input' => 'men_seizures'),
            array('expected' => 'menInabilityFeed', 'input' => 'men_inability_feed'),
            array('expected' => 'menNeckStiff', 'input' => 'men_neck_stiff'),
            array('expected' => 'csfId', 'input' => 'CSF_ID'),
            array('expected' => 'csfLabDate', 'input' => 'CSF_lab_date'),
            array('expected' => 'csfWcc', 'input' => 'CSF_WCC'),
            array('expected' => 'caseId', 'input' => 'cAse!ID'),
            array('expected' => 'menSeizures', 'input' => 'men # seizures'),
            array('expected' => 'menInabilityFeed', 'input' => 'men  inability  feed'),
            array('expected' => 'menNeckStiff', 'input' => 'men  neck  stiff'),
            array('expected' => 'csfId', 'input' => 'CSF  ID'),
            array('expected' => 'csfLabDate', 'input' => 'CSF  lab  date'),
            array('expected' => 'csfWcc', 'input' => 'CSF  WCC'),
        );
    }

    public function testSetHeaderIsCalled()
    {
        $mockReader = $this->getMockBuilder('NS\ImportBundle\Reader\CsvReader')
            ->disableOriginalConstructor()
            ->getMock();

        $mockReader->expects($this->once())
            ->method('setHeaderRowNumber')
            ->with(0);
        $mockReader->expects($this->once())
            ->method('getColumnHeaders')
            ->willReturn(array('columnOne', 'columnTwo'));

        $mockReaderFactory = $this->getMock('NS\ImportBundle\Reader\ReaderFactory');
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
