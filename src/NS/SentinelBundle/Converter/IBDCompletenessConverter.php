<?php

namespace NS\SentinelBundle\Converter;

use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Validators\Done;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class IBDCompletenessConverter
 * @package NS\SentinelBundle\Converter
 */
class IBDCompletenessConverter extends BaseCompletenessConverter
{
    /**
     * @inheritDoc
     */
    public function __invoke($item)
    {
        $this->message = null;

        $this->handleFields($item,$this->constraints['case']);

        return $item;
    }

    /**
     * @param array $item
     * @param array $configs
     * @return array
     */
    public function handleFields(array &$item,array $configs)
    {
        $constraints = array();
        foreach($configs as $config) {
            $constraints[] = new Done($config);
        }

        $violationList = $this->validator->validate($item, $constraints, array('Default'));
        if ($violationList) {
            $this->addMessages($item, $violationList);
        }
    }

    /**
     * @param array $item
     * @param ConstraintViolationListInterface $violations
     */
    public function addMessages(array &$item, ConstraintViolationListInterface $violations)
    {
        foreach ($violations as $violation) {
            $this->message = $violation->getMessage();

            $item[$violation->getPropertyPath()] = new TripleChoice(TripleChoice::YES);
        }
    }
}
