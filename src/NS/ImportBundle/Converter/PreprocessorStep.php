<?php

namespace NS\ImportBundle\Converter;

use Ddeboer\DataImport\Report;
use Ddeboer\DataImport\Step;
use NS\ImportBundle\Converter\Expression\ExpressionBuilder;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class ExpressionConverter
 * @package NS\ImportBundle\Converter
 */
class PreprocessorStep implements Step
{
    /**
     * @var ExpressionLanguage
     */
    private $language;

    /**
     * @var ExpressionBuilder
     */
    private $expressionBuilder;

    /**
     * @var array
     */
    private $conditions = array();

    /**
     * ExpressionConverter constructor.
     * @param ExpressionBuilder $builder
     */
    public function __construct(ExpressionBuilder $builder)
    {
        $this->expressionBuilder = $builder;
        $this->language = new ExpressionLanguage();
    }

    /**
     * @param string $property
     * @param mixed $conditions
     * @return $this|void
     */
    public function add($property, $conditions)
    {
        $this->conditions[$property] = (is_array($conditions)) ? $conditions : array($conditions);
    }

    /**
     * @inheritDoc
     */
    public function process(&$item, Report $report = null)
    {
        $accessor = new PropertyAccessor();

        foreach ($this->conditions as $property => $conditions) {
            foreach ($conditions as $condition) {
                $expr = $this->expressionBuilder->convertRuleToExpression($condition->getRule());
                if ($this->language->evaluate($expr, array('item' => $item))) {
                    $accessor->setValue($item, sprintf('[%s]', $property), $condition->getValue());
                    break;
                }
            }
        }

        return true;
    }
}
