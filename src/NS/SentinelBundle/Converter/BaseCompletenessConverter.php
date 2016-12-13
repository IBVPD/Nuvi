<?php

namespace NS\SentinelBundle\Converter;

use Ddeboer\DataImport\ReporterInterface;
use Symfony\Component\Validator\Validator\RecursiveValidator;

abstract class BaseCompletenessConverter implements ReporterInterface
{
    /**
     * @var
     */
    protected $message;

    /**
     * @var RecursiveValidator
     */
    protected $validator;

    /**
     * @var array
     */
    protected $constraints = ['case' => [], 'siteLab' => [], 'externalLab' => []];

    /**
     * BaseCompletenessConverter constructor.
     * @param RecursiveValidator $validator
     * @param array $configration
     */
    public function __construct(RecursiveValidator $validator, array $configration)
    {
        $this->validator = $validator;
        $this->constraints = $configration;
    }

    /**
     * @param array $item
     * @param array $configs
     * @return
     */
    abstract public function handleFields(array &$item, array $configs);

     /**
     * @return bool
     */
    public function hasMessage()
    {
        return !empty($this->message);
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getSeverity()
    {
        return ReporterInterface::WARNING;
    }
}
