<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 10/01/19
 * Time: 9:40 AM
 */

namespace NS\ImportBundle\Formatter;

use Exporter\Formatter\DataFormatterInterface;
use NS\SentinelBundle\Form\IBD\Types\HiSerotype;
use NS\SentinelBundle\Form\IBD\Types\NmSerogroup;
use NS\SentinelBundle\Form\IBD\Types\SpnSerotype;
use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\PropertyAccess\PropertyPath;

class SeroTypeGroupFormatter implements DataFormatterInterface
{
    public function supports($data): bool
    {
        return $data instanceof SpnSerotype || $data instanceof NmSerogroup || $data instanceof HiSerotype;
    }

    public function format($data, PropertyPath $propertyPath)
    {
        if ($data->equal(ArrayChoice::NO_SELECTION)) {
            return null;
        }

        return (string)$data;
    }

    public function getPriority(): int
    {
        return 4;
    }

}
