<?php


namespace NS\SentinelBundle\Validators;

use InvalidArgumentException;
use NS\SentinelBundle\Entity\Meningitis\Meningitis;
use NS\SentinelBundle\Entity\Meningitis\SiteLab as MeningitisSiteLab;
use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
use NS\SentinelBundle\Entity\Pneumonia\SiteLab as PneumoniaSiteLab;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SecondBloodValidator extends ConstraintValidator
{
    /**
     * @param mixed                  $value
     * @param Constraint|SecondBlood $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        $obj = $this->context->getObject();
        if ($obj instanceof PneumoniaSiteLab || $obj instanceof MeningitisSiteLab) {
            /** @var Pneumonia|Meningitis $case */
            $case = $obj->getCaseFile();
            if (empty($value) && $case->getBloodNumberOfSamples() === 2) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
            return;
        }

        if ($obj instanceof Meningitis || $obj instanceof Pneumonia) {
            if (empty($value) && $obj->getBloodNumberOfSamples() === 2) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
            return;
        }

        throw new InvalidArgumentException('Expected on SiteLab instances');
    }
}
