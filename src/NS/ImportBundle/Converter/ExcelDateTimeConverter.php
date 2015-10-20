<?php

namespace NS\ImportBundle\Converter;

use \Ddeboer\DataImport\ValueConverter\ExcelDateTimeConverter as BaseDateConverter;

/**
 * Class ExcelDateConverter
 * @package NS\ImportBundle\Controller
 */
class ExcelDateTimeConverter extends BaseDateConverter implements NamedValueConverterInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Excel DateTime';
    }
}
