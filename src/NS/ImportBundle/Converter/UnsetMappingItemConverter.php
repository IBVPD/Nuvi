<?php

namespace NS\ImportBundle\Converter;

use \Ddeboer\DataImport\Report;
use \Ddeboer\DataImport\Step;
use \Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Description of UnsetMappingItemConverter
 *
 * @author gnat
 */
class UnsetMappingItemConverter implements Step
{
    /**
     * @var array
     */
    private $mappings = [];

    /**
     * @param array            $mappings
     * @param PropertyAccessor $accessor
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(array $mappings = [], PropertyAccessor $accessor = null)
    {
        $this->mappings = $mappings;
    }

    /**
     * @param string $from
     * @param string $to
     *
     * @return $this
     */
    public function map($from, $to)
    {
        $this->mappings[$from] = $to;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function process(&$item, Report $result = null)
    {
        foreach (array_keys($this->mappings) as $from) {
            if(isset($item[$from])) {
                unset($item[$from]);
            }
        }
    }
}
