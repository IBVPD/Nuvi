<?php

namespace NS\ImportBundle\Converter;

use Ddeboer\DataImport\Report;
use Ddeboer\DataImport\Step;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class UnsetMappingItemConverter implements Step
{
    /** @var array */
    private $mappings;

    public function __construct(array $mappings = [])
    {
        $this->mappings = $mappings;
    }

    public function map(string $from, string $to): void
    {
        $this->mappings[] = $from;
    }

    public function process(&$item, Report $result = null)
    {
        foreach ($this->mappings as $from) {
            if (isset($item[$from])) {
                unset($item[$from]);
            }
        }
    }
}
