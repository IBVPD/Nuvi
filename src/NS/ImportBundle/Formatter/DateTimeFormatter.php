<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 16/03/17
 * Time: 10:36 AM
 */

namespace NS\ImportBundle\Formatter;

use Exporter\Formatter\DataFormatterInterface;
use Symfony\Component\PropertyAccess\PropertyPath;

class DateTimeFormatter implements DataFormatterInterface
{
    /** @var string */
    private $defaultDateFormat = 'Y-m-d';

    /** @var array */
    private $fields = [
        'createdAt' => 'Y-m-d H:i:s',
        'updatedAt' => 'Y-m-d H:i:s',
        'csf_collect_time' => 'H:i',
        'blood_collect_time' => 'H:i',
        'blood_second_collect_time' => 'H:i',
        'pleural_fluid_collect_time' => 'H:i',
        'siteLab.received' => 'Y-m-d H:i',
        'siteLab.csf_lab_time' => 'H:i',
        'siteLab.blood_lab_time' => 'H:i',
        'siteLab.blood_second_lab_time' => 'H:i',
        'siteLab.other_lab_time' => 'H:i',
        'siteLab.createdAt' => 'Y-m-d H:i:s',
        'siteLab.updatedAt' => 'Y-m-d H:i:s',
        'referenceLab.createdAt' => 'Y-m-d H:i:s',
        'referenceLab.updatedAt' => 'Y-m-d H:i:s',
        'nationalLab.createdAt' => 'Y-m-d H:i:s',
        'nationalLab.updatedAt' => 'Y-m-d H:i:s',
    ];

    public function __construct(string $defaultDateFormat = 'Y-m-d', array $fields = [])
    {
        $this->defaultDateFormat = $defaultDateFormat;
        $this->fields = array_merge($this->fields, $fields);
    }

    public function supports($data)
    {
        return $data instanceof \DateTime || $data instanceof \DateTimeInterface;
    }

    public function format($data, PropertyPath $propertyPath): string
    {
        if (isset($this->fields[(string)$propertyPath])) {
            return $data->format($this->fields[(string)$propertyPath]);
        }

        return $data->format($this->defaultDateFormat);
    }

    public function getPriority(): int
    {
        return 50;
    }
}
