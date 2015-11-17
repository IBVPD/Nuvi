<?php

namespace NS\ImportBundle\Reader;

use Ddeboer\DataImport\Reader\ExcelReader as BaseReader;

class ExcelReader extends BaseReader implements OffsetableReaderInterface
{
    /**
     * @var integer
     */
    protected $offset;

    /**
     * @inheritDoc
     */
    public function setOffset($offset)
    {
        if($this->headerRowNumber !== null) {
            $offset--;
        }

        $this->offset = $offset;
    }

    /**
     * @inheritDoc
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        parent::rewind();

        if($this->offset > 0) {
            $this->pointer += $this->offset;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return ($this->pointer < $this->maxRow+$this->headerRowNumber);
    }

    /**
     * @inheritDoc
     */
    public function setColumnHeaders(array $columnHeaders)
    {
        array_walk($columnHeaders,array($this,'cleanColumnHeaders'));
        parent::setColumnHeaders($columnHeaders);
    }

    /**
     * @param $item
     * @param $key
     * @param $prefix
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function cleanColumnHeaders(&$item, $key, $prefix)
    {
        $item =  preg_replace('/[\x00-\x1F\x80-\xFF]/', '', trim($item));
    }
}
