<?php

namespace NS\SentinelBundle\Exceptions;

use Ddeboer\DataImport\Exception\ExceptionInterface;
use RuntimeException;

/**
 * Description of NonExistentSite
 *
 * @author gnat
 */
class NonExistentSite extends RuntimeException implements ExceptionInterface
{
}
