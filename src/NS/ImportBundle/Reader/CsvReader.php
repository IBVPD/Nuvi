<?php

namespace NS\ImportBundle\Reader;

use Ddeboer\DataImport\Reader\CsvReader as BaseReader;

class CsvReader extends BaseReader implements OffsetableReaderInterface
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
        if ($this->offset > 0) {
            $this->seek($this->offset);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        if (null === $this->count) {
            $position = $this->key();

            $this->count = 0;
            // if we call any iterator on ourselves, our offset gets introduced which causes rewind to be called
            // and then invalid count response is returned
            $this->count = iterator_count($this->file);


            if (null !== $this->headerRowNumber) {
                $this->count -= $this->headerRowNumber + 1;
            }

            $this->seek($position);
        }

        return $this->count;
    }
}
