<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 21/04/16
 * Time: 4:42 PM
 */

namespace NS\ImportBundle\Converter;


class TimeValueConverter extends DateTimeValueConverter
{
    /**
     * @inheritDoc
     */
    public function __construct($inputFormat = null, $outputFormat = null)
    {
        parent::__construct($inputFormat, $outputFormat);
        $this->name = 'Time: '.$inputFormat;
    }
}
