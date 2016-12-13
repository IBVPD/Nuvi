<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 05/05/16
 * Time: 11:08 AM
 */

namespace NS\SentinelBundle\Validators;

use NS\SentinelBundle\Form\Types\Role;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ACLValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     * @param \NS\SentinelBundle\Entity\ACL $acl
     * @param \NS\SentinelBundle\Validators\ACL $constraint
     */
    public function validate($acl, Constraint $constraint)
    {
        switch ($acl->getType()->getValue()) {
            case Role::REGION_API:
                $this->violate($constraint->message,'REGION','Api Access');
                break;
            case Role::COUNTRY_API:
                $this->violate($constraint->message,'COUNTRY','Api Access');
                break;
            case Role::SITE_API:
                $this->violate($constraint->message,'SITE','Api Access');
                break;
            case Role::REGION_IMPORT:
                $this->violate($constraint->message,'REGION','Import Access');
                break;
            case Role::COUNTRY_IMPORT:
                $this->violate($constraint->message,'COUNTRY','Import Access');
                break;
            case Role::SITE_IMPORT:
                $this->violate($constraint->message,'SITE','Import Access');
                break;
        }
    }

    /**
     * @param $message
     * @param $type
     * @param $option
     */
    private function violate($message, $type, $option)
    {
        $this->context
            ->buildViolation($message)
            ->setParameter('%type%',$type)
            ->setParameter('%option%',$option)
            ->atPath('type')
            ->addViolation();
    }
}
