<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 16/03/17
 * Time: 10:45 AM
 */

namespace NS\ImportBundle\Formatter;

use Exporter\Formatter\DataFormatterInterface;
use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\PropertyAccess\PropertyPath;

class ArrayChoiceFormatter implements DataFormatterInterface
{
    /**
     * @inheritDoc
     */
    public function supports($data)
    {
        return $data instanceof ArrayChoice;
    }

    /**
     * @inheritDoc
     * @param ArrayChoice $data
     */
    public function format($data, PropertyPath $propertyPath)
    {
        return $data->getValue();
    }

    /**
     * @inheritDoc
     */
    public function getPriority()
    {
        return 75;
    }
}
