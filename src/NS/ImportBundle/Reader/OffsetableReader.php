<?php

namespace NS\ImportBundle\Reader;

interface OffsetableReader
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
