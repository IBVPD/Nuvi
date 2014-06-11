<?php

namespace NS\SentinelBundle\Converter;

use \NS\ImportBundle\Converter\NamedValueConverterInterface;
use NS\SentinelBundle\Form\Types\TripleChoice as TChoice;
/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class TripleChoice implements NamedValueConverterInterface
{
    public function convert($input)
    {
        return new TChoice($input);
    }

    public function getName()
    {
        return 'TripleChoice';
    }
}
