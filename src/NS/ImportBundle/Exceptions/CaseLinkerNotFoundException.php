<?php

namespace NS\ImportBundle\Exceptions;

use Exception;
use RuntimeException;

class CaseLinkerNotFoundException extends RuntimeException
{
    /**
     * CaseLinkerNotFoundException constructor.
     * @param string $id
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($id = "", $code = 0, Exception $previous = null)
    {
        parent::__construct(sprintf('Unable to locate case linker with service id "%s"', $id), $code, $previous);
    }
}
