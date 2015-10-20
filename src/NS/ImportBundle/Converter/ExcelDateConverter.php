<?php

namespace NS\ImportBundle\Converter;

use \Ddeboer\DataImport\ValueConverter\ExcelDateConverter as BaseDateConverter;

/**
 * Class ExcelDateConverter
 * @package NS\ImportBundle\Controller
 */
class ExcelDateConverter extends BaseDateConverter implements NamedValueConverterInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Excel Date';
    }
}
