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
        'siteLab.received' => 'Y-m-d H:i',
        'siteLab.csf_lab_time' => 'H:i',
        'siteLab.blood_lab_time' => 'H:i',
        'siteLab.other_lab_time' => 'H:i',
        'csf_collect_time' => 'H:i',
        'blood_collect_time' => 'H:i',
        'pleural_fluid_collect_time' => 'H:i',
    ];

    /**
     * DateTimeFormatter constructor.
     * @param string $defaultDateFormat
     * @param array $fields
     */
    public function __construct($defaultDateFormat = 'Y-m-d', array $fields = [])
    {
        $this->defaultDateFormat = $defaultDateFormat;
        $this->fields = array_merge($this->fields, $fields);
    }

    /**
     * @inheritDoc
     */
    public function supports($data)
    {
        return $data instanceof \DateTime || $data instanceof \DateTimeInterface;
    }

    /**
     * @inheritDoc
     */
    public function format($data, PropertyPath $propertyPath)
    {
        if (isset($this->fields[(string)$propertyPath])) {
            return $data->format($this->fields[(string)$propertyPath]);
        }

        return $data->format($this->defaultDateFormat);
    }

    /**
     * @inheritDoc
     */
    public function getPriority()
    {
        return 50;
    }
}
