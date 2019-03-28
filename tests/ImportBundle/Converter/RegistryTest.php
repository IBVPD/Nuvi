<?php

namespace NS\ImportBundle\Tests\Converter;

use NS\ImportBundle\Converter\DateTimeValueConverter;
use NS\ImportBundle\Converter\Registry;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Description of RegistryTest
 *
 * @author gnat
 */
class RegistryTest extends TypeTestCase
{
    /** @var Registry */
    private $type;

    protected function setUp()
    {
        $converters = [
            'ns_import.converter.date.who'            => new DateTimeValueConverter('D M d H:i:s e Y'),
            'ns_import.converter.date.timestamp'      => new DateTimeValueConverter('Y-m-d H:i:s'),
            'ns_import.converter.date.year_month_day' => new DateTimeValueConverter('Y/m/d|'),
            'ns_import.converter.date.month_day_year' => new DateTimeValueConverter('m/d/Y|'),
        ];

        $this->type = new Registry();
        foreach ($converters as $id => $converter) {
            $this->type->addConverter($id, $converter);
        }

        parent::setUp();
    }


    public function testRegistry()
    {
        $form = $this->factory->create(Registry::class);
        $this->assertCount(4, $form->getConfig()->getOption('choices'));
        $this->assertEquals('ns_import.converter.date.who', $this->type->getConverterForField('Date: D M d H:i:s e Y'));
    }

    protected function getExtensions()
    {
        return [ new PreloadedExtension([$this->type], []) ];
    }
}
