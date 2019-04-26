<?php

namespace NS\SentinelBundle\Converter;

use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Validators\Done;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class IBDCompletenessConverter extends BaseCompletenessConverter
{
    /**
     * @inheritDoc
     */
    public function __invoke($item)
    {
        $this->message = null;

        $this->handleFields($item, $this->constraints['case']);

        return $item;
    }

    public function handleFields(array &$item, array $configs): void
    {
        $constraints = [];
        foreach ($configs as $config) {
            $constraints[] = new Done($config);
        }

        $violationList = $this->validator->validate($item, $constraints, ['Default']);
        if ($violationList) {
            $this->addMessages($item, $violationList);
        }
    }

    public function addMessages(array &$item, ConstraintViolationListInterface $violations): void
    {
        foreach ($violations as $violation) {
            $this->message = $violation->getMessage();

            $item[$violation->getPropertyPath()] = new TripleChoice(TripleChoice::YES);
        }
    }
}
