<?php

namespace NS\SentinelBundle\Tests\Entity;

use NS\SentinelBundle\Form\Types\TripleChoice;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseRVTest extends TestCase
{
    /** @var ValidatorInterface */
    protected $validator;
    protected $tripleChoiceYes;
    protected $tripleChoiceNo;

    abstract protected function getBaseValidEntity();

    public function setUp()
    {
        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $this->tripleChoiceYes = new TripleChoice(TripleChoice::YES);
        $this->tripleChoiceNo = new TripleChoice(TripleChoice::NO);
    }

    protected function countViolations(ConstraintViolationListInterface $violations): array
    {
        $types = [];
        /**
         * @var ConstraintViolation $v
         */
        foreach ($violations->getIterator() as $v)
        {
            $class = get_class($v->getConstraint());
            $types[$class] = isset($types[$class]) ? $types[$class] + 1 : 1;
        }

        return $types;
    }

    protected function mapViolations(ConstraintViolationListInterface $violations): array
    {
        return array_map(static function (ConstraintViolation $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($violations->getIterator()));
    }

    protected function _testOtherConstraints(array $req): void
    {
        foreach($req as $fieldname => $field)
        {
            foreach($field['parents'] as $parent => $values)
            {
                foreach($values as $value)
                {
                    $expected_count = count($req);

                    $lab = $this->getBaseValidEntity();
                    $method = 'set'.str_replace('_', '', ucwords($parent, '_'));
                    $lab->$method($value);
                    $violations = $this->mapViolations($this->validator->validate($lab, null, ['Completeness']));

                    $message = get_class($lab).'::'.$parent.' with value "'.$value.'" requires '.$fieldname;
                    self::assertCount($expected_count, $violations, $message);
                    self::assertContains($fieldname, $violations, $message);

                    $method = 'set'.str_replace('_', '', ucwords($fieldname, '_'));
                    $lab->$method($field['pass_value']);
                    $violations = $this->mapViolations($this->validator->validate($lab, null, ['Completeness']));

                    self::assertCount($expected_count - 1, $violations, $message);
                }
            }
        }
    }
}
