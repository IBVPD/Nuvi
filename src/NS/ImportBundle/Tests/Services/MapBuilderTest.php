<?php

namespace NS\ImportBundle\Tests\Services;

use \NS\ImportBundle\Converter\Registry;
use \NS\ImportBundle\Services\MapBuilder;
use \NS\SentinelBundle\Converter\ArrayChoice;
use \NS\SentinelBundle\Converter\Doses;

/**
 * Description of MapBuilderTest
 *
 * @author gnat
 */
class MapBuilderTest extends \Liip\FunctionalTestBundle\Test\WebTestCase
{

    public function testProcessFile()
    {
        $mapBuilder = new MapBuilder();
        $this->addConverters($mapBuilder);
        $ibdClass   = 'NS\SentinelBundle\Entity\IBD';
        $meta       = $this->getContainer()->get('doctrine.orm.entity_manager')->getClassMetadata('NS\SentinelBundle\Entity\IBD');
        $map        = new \NS\ImportBundle\Entity\Map();
        $file       = new \Symfony\Component\HttpFoundation\File\UploadedFile(__DIR__ . '/../Fixtures/IBD.csv', 'IBD.csv');

        $map->setClass($ibdClass);
        $map->setName('Test IBD From File');
        $map->setVersion('1.0');
        $map->setFile($file);

        $this->assertEquals('Test IBD From File', $map->getName());
        $this->assertEquals('1.0', $map->getVersion());
        $this->assertEquals($file, $map->getFile());
        $this->assertEquals(sprintf("%s %s", $map->getName(), $map->getVersion()), $map->__toString());

        $mapBuilder->process($map, $meta);
        $columns    = $map->getColumns();
        $this->assertGreaterThan(0, $columns->count());
        $this->assertTrue($columns[0]->isIgnored());
        $this->assertTrue($columns[1]->isIgnored());
        $this->assertEquals($mapBuilder->camelCase($columns[2]->getName()), $columns[2]->getMapper());
        $this->assertEquals($mapBuilder->camelCase($columns[3]->getName()), $columns[3]->getMapper());
        $this->assertNotNull($columns[3]->getConverter());
        $this->assertEquals('ns.sentinel.converter.gender', $columns[3]->getConverter());

        $headers = $map->getColumnHeaders();
        foreach ($headers as $index => $name)
            $this->assertEquals($columns[$index]->getName(), $name);
    }

    private function addConverters(MapBuilder $builder)
    {
        $converters = array(
            'ns.sentinel.converter.volume'                        => new ArrayChoice('NS\SentinelBundle\Form\Types\Volume'),
            'ns.sentinel.converter.spnSerotype'                   => new ArrayChoice('NS\SentinelBundle\Form\Types\SpnSerotype'),
            'ns.sentinel.converter.serotypeIdentifier'            => new ArrayChoice('NS\SentinelBundle\Form\Types\SerotypeIdentifier'),
            'ns.sentinel.converter.rotavirusVaccinationReceived'  => new ArrayChoice('NS\SentinelBundle\Form\Types\RotavirusVaccinationReceived'),
            'ns.sentinel.converter.VaccinationReceived'           => new ArrayChoice('NS\SentinelBundle\Form\Types\VaccinationReceived'),
            'ns.sentinel.converter.threeDoses'                    => new Doses('NS\SentinelBundle\Form\Types\ThreeDoses'),
            'ns.sentinel.converter.fourDoses'                     => new Doses('NS\SentinelBundle\Form\Types\FourDoses'),
            'ns.sentinel.converter.rotavirusDischargeOutcome'     => new ArrayChoice('NS\SentinelBundle\Form\Types\RotavirusDischargeOutcome'),
            'ns.sentinel.converter.rehydration'                   => new ArrayChoice('NS\SentinelBundle\Form\Types\Rehydration'),
            'ns.sentinel.converter.pathogenIdentifier'            => new ArrayChoice('NS\SentinelBundle\Form\Types\PathogenIdentifier'),
            'ns.sentinel.converter.pcvType'                       => new ArrayChoice('NS\SentinelBundle\Form\Types\PCVType'),
            'ns.sentinel.converter.pcrResult'                     => new ArrayChoice('NS\SentinelBundle\Form\Types\PCRResult'),
            'ns.sentinel.converter.otherSpecimen'                 => new ArrayChoice('NS\SentinelBundle\Form\Types\OtherSpecimen'),
            'ns.sentinel.converter.nmSerogroup'                   => new ArrayChoice('NS\SentinelBundle\Form\Types\NmSerogroup'),
            'ns.sentinel.converter.MeningVaccinationType'         => new ArrayChoice('NS\SentinelBundle\Form\Types\MeningitisVaccinationType'),
            'ns.sentinel.converter.meningitisVaccinationReceived' => new ArrayChoice('NS\SentinelBundle\Form\Types\MeningitisVaccinationReceived'),
            'ns.sentinel.converter.latResult'                     => new ArrayChoice('NS\SentinelBundle\Form\Types\LatResult'),
            'ns.sentinel.converter.isolateType'                   => new ArrayChoice('NS\SentinelBundle\Form\Types\IsolateType'),
            'ns.sentinel.converter.ibdCaseResult'                 => new ArrayChoice('NS\SentinelBundle\Form\Types\IBDCaseResult'),
            'ns.sentinel.converter.hiSerotype'                    => new ArrayChoice('NS\SentinelBundle\Form\Types\HiSerotype'),
            'ns.sentinel.converter.gramStainOrganism'             => new ArrayChoice('NS\SentinelBundle\Form\Types\GramStainOrganism'),
            'ns.sentinel.converter.gramStain'                     => new ArrayChoice('NS\SentinelBundle\Form\Types\GramStain'),
            'ns.sentinel.converter.genotypeResultP'               => new ArrayChoice('NS\SentinelBundle\Form\Types\GenotypeResultP'),
            'ns.sentinel.converter.genotypeResultG'               => new ArrayChoice('NS\SentinelBundle\Form\Types\GenotypeResultG'),
            'ns.sentinel.converter.elisaKit'                      => new ArrayChoice('NS\SentinelBundle\Form\Types\ElisaKit'),
            'ns.sentinel.converter.elisaResult'                   => new ArrayChoice('NS\SentinelBundle\Form\Types\ElisaResult'),
            'ns.sentinel.converter.dischargeOutcome'              => new ArrayChoice('NS\SentinelBundle\Form\Types\DischargeOutcome'),
            'ns.sentinel.converter.dischargeClassification'       => new ArrayChoice('NS\SentinelBundle\Form\Types\DischargeClassification'),
            'ns.sentinel.converter.diagnosis'                     => new ArrayChoice('NS\SentinelBundle\Form\Types\Diagnosis'),
            'ns.sentinel.converter.dehydration'                   => new ArrayChoice('NS\SentinelBundle\Form\Types\Dehydration'),
            'ns.sentinel.converter.cultureResult'                 => new ArrayChoice('NS\SentinelBundle\Form\Types\CultureResult'),
            'ns.sentinel.converter.caseStatus'                    => new ArrayChoice('NS\SentinelBundle\Form\Types\CaseStatus'),
            'ns.sentinel.converter.cxrResult'                     => new ArrayChoice('NS\SentinelBundle\Form\Types\CXRResult'),
            'ns.sentinel.converter.cxrAdditionalResult'           => new ArrayChoice('NS\SentinelBundle\Form\Types\CXRAdditionalResult'),
            'ns.sentinel.converter.binaxResult'                   => new ArrayChoice('NS\SentinelBundle\Form\Types\BinaxResult'),
            'ns.sentinel.converter.csfAppearance'                 => new ArrayChoice('NS\SentinelBundle\Form\Types\CSFAppearance'),
            'ns.sentinel.converter.gender'                        => new ArrayChoice('NS\SentinelBundle\Form\Types\Gender'),
            'ns.sentinel.converter.diagnosis'                     => new ArrayChoice('NS\SentinelBundle\Form\Types\Diagnosis'),
            'ns.sentinel.converter.triple_choice'                 => new ArrayChoice('NS\SentinelBundle\Form\Types\TripleChoice'),
        );

        $converterRegistry = new Registry();
        foreach ($converters as $id => $converter)
            $converterRegistry->addConverter($id, $converter);

        $builder->setConverterRegistry($converterRegistry);
    }

    /**
     *
     * @param string $expected
     * @param string $input
     * @dataProvider camelCaseProvider
     */
    public function testCamelCase($expected, $input)
    {
        $mapBuilder = new MapBuilder();
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
}