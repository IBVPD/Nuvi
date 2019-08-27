<?php

namespace NS\SentinelBundle\Validators;

use NS\SentinelBundle\Entity\RotaVirus;
use NS\SentinelBundle\Form\RotaVirus\Types\DischargeClassification;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RotaVirusCompleteValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof RotaVirus) {
            throw new \InvalidArgumentException(sprintf('Was expecting object of type %s got %s instead', RotaVirus::class, is_object($value) ? get_class($value) : gettype($value)));
        }

        if (!$value->getDischargeClassification() || $value->getDischargeClassification()->equal(DischargeClassification::NO_SELECTION)) {
            /** @var RotaVirus\SiteLab|null $lab */
            $lab = $value->getSiteLab();
            if ($lab && $lab->getElisaResult() && !$lab->getElisaResult()->equal(ElisaResult::NO_SELECTION)) {
                $this->context
                    ->buildViolation('Site lab has an ELISA result thus the discharge classification should be set as well')
                    ->atPath('dischargeClassification')
                    ->addViolation();
            }

            /** @var RotaVirus\NationalLab|null $lab */
            $lab = $value->getNationalLab();
            if ($lab && $lab->getElisaResult() && !$lab->getElisaResult()->equal(ElisaResult::NO_SELECTION)) {
                $this->context
                    ->buildViolation('National lab has an ELISA result thus the discharge classification should be set as well')
                    ->atPath('dischargeClassification')
                    ->addViolation();
            }
        }
    }
}
