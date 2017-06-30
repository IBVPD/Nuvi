<?php

namespace NS\ImportBundle\Converter;

/**
 *
 * @author gnat
 */
interface NamedValueConverterInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getType();
}
