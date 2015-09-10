<?php

namespace NS\ImportBundle\Tests\Converter;

use \NS\ImportBundle\Converter\DateTimeValueConverter;
use \NS\ImportBundle\Converter\Registry;
use \Symfony\Component\Form\Test\TypeTestCase;

/**
 * Description of RegistryTest
 *
 * @author gnat
 */
class RegistryTest extends TypeTestCase
{
    public function testRegistry()
    {
        $converters = array(
            'ns_import.converter.date.who'            => new DateTimeValueConverter("D M d H:i:s e Y"),
            'ns_import.converter.date.timestamp'      => new DateTimeValueConverter("Y-m-d H:i:s"),
            'ns_import.converter.date.year_month_day' => new DateTimeValueConverter("Y/m/d|"),
            'ns_import.converter.date.month_day_year' => new DateTimeValueConverter("m/d/Y|"),
        );

        $type = new Registry();
        foreach ($converters as $id => $converter) {
            $type->addConverter($id, $converter);
        }

        $form = $this->factory->create($type);
        $this->assertCount(4, $form->getConfig()->getOption('choices'));
        $this->assertEquals('ns_import.converter.date.who', $type->getConverterForField('Date: D M d H:i:s e Y'));
    }
}
