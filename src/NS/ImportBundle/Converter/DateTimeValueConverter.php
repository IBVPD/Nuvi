<?php

namespace NS\ImportBundle\Converter;

use Ddeboer\DataImport\ValueConverter\DateTimeValueConverter as BaseDateTimeValueConverter;

/**
 * Description of DataeTimeValueConverter
 *
 * @author gnat
 */
class DateTimeValueConverter extends BaseDateTimeValueConverter implements NamedValueConverterInterface
{
    private $name;

    /**
     * @param string $inputFormat
     * @param string $outputFormat
     */
    public function __construct($inputFormat = null, $outputFormat = null)
    {
        parent::__construct($inputFormat,$outputFormat);

        $this->name = 'Date: '.$inputFormat;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
