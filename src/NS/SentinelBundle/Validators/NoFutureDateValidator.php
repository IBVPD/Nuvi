<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 21/02/17
 * Time: 4:31 PM
 */

namespace NS\SentinelBundle\Validators;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NoFutureDateValidator extends ConstraintValidator
{
    /** @var \DateTime */
    private $today;

    public function __construct()
    {
        $this->today = new \DateTime();
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value instanceof \DateTime) {
            if($value > $this->today) {
                $this->context->buildViolation('This date is in the future')->addViolation();

            }
        }
    }
}
