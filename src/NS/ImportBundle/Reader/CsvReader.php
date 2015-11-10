<?php

namespace NS\ImportBundle\Reader;

use Ddeboer\DataImport\Reader\CsvReader as BaseReader;

class CsvReader extends BaseReader
{
    /**
     * @var integer
     */
    protected $offset;


    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     * @return CsvReader
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        parent::rewind();
        if($this->offset > 0) {
            $this->seek($this->offset);
        }
    }
}