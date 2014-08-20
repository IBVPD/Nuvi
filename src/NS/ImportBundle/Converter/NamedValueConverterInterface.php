<?php

namespace NS\ImportBundle\Converter;

use Ddeboer\DataImport\ValueConverter\ValueConverterInterface;

/**
 *
 * @author gnat
 */
interface NamedValueConverterInterface extends ValueConverterInterface
{
    public function getName();
}
