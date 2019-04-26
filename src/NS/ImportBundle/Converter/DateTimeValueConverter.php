<?php

namespace NS\ImportBundle\Converter;

use DateTime;
use Ddeboer\DataImport\ValueConverter\DateTimeValueConverter as BaseDateTimeValueConverter;

/**
 * Description of DataeTimeValueConverter
 *
 * @author gnat
 */
class DateTimeValueConverter extends BaseDateTimeValueConverter implements NamedValueConverterInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /** @var ExcelDateTimeConverter */
    private $excelConverter;

    /**
     * @param string $inputFormat
     * @param string $outputFormat
     */
    public function __construct($inputFormat = null, $outputFormat = null)
    {
        parent::__construct($inputFormat, $outputFormat);

        $this->name = 'Date: ' . $inputFormat;
        $this->excelConverter = new ExcelDateTimeConverter($outputFormat);
    }

    /**
     * @inheritDoc
     */
    public function __invoke($input)
    {
        // Try ExcelDate
        if (is_numeric($input)) {
            $result = $this->excelConverter->__invoke($input);
            if ($result instanceof DateTime) {
                return $result;
            }
        }

        return parent::__invoke($input);
    }


    /**
     * @return string
     */
    public function getName()
    {
        if ($this->description) {
            return sprintf('%s (%s)', $this->name, $this->description);
        }

        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return $this->getName();
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}
