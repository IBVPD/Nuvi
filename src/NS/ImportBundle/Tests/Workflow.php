<?php

namespace NS\ImportBundle\Tests;

use \Ddeboer\DataImport\Workflow as BaseWorkflow;

/**
 * Description of Workflow
 *
 * @author gnat
 */
class Workflow extends BaseWorkflow
{
    public function getValueConverters()
    {
        return $this->valueConverters;
    }

    public function getItemConverters()
    {
        return $this->itemConverters;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function getAfterConversionFilters()
    {
        return $this->afterConversionFilters;
    }
}