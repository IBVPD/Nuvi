<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 20/12/18
 * Time: 4:35 PM
 */

namespace NS\SentinelBundle\Validators;

use NS\SentinelBundle\Entity\RotaVirus;
use NS\SentinelBundle\Form\Types\TripleChoice;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TacPhaseTwoValidator extends ConstraintValidator
{
    /**
     * @param RotaVirus              $value
     * @param TacPhaseTwo|Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value === null || $value === '') {
            return;
        }

        if ($value->getSite() && $value->getSite()->isTacPhase2()) {
            if (!$value->getSympDiaBloody() || $value->getSympDiaBloody()->equal(TripleChoice::NO_SELECTION)) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->atPath('symptomDiarrheaBloody')
                    ->addViolation();
            }

            if (!$value->getSympDiarrhea() || $value->getSympDiarrhea()->equal(TripleChoice::NO_SELECTION)) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->atPath('symptomDiarrhea')
                    ->addViolation();
            }
        }
    }
}
