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

}
