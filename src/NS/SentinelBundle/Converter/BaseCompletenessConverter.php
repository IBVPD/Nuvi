<?php

namespace NS\SentinelBundle\Converter;

use Ddeboer\DataImport\ReporterInterface;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseCompletenessConverter implements ReporterInterface
{
    /**
     * @var
     */
    protected $message;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var array
     */
    protected $constraints = ['case' => [], 'siteLab' => [], 'externalLab' => []];

    /**
     * BaseCompletenessConverter constructor.
     * @param ValidatorInterface $validator
     * @param array $constraints
     */
    public function __construct(ValidatorInterface $validator, array $constraints)
    {
        $this->validator = $validator;
        $this->constraints = $constraints;
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
