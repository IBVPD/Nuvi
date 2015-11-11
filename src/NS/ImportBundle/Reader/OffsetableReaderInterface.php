<?php

namespace NS\ImportBundle\Reader;

interface OffsetableReaderInterface
{

    /**
     * @param $offset
     * @return mixed
     */
    public function setOffset($offset);

    /**
     * @return mixed
     */
    public function getOffset();
}
