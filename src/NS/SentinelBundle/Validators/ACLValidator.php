<?php

namespace NS\SentinelBundle\Validators;

use NS\SentinelBundle\Entity\ACL as AclObject;
use NS\SentinelBundle\Form\Types\Role;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ACLValidator extends ConstraintValidator
{
    /**
     * @param AclObject      $acl
     * @param Constraint|ACL $constraint
     */
    public function validate($acl, Constraint $constraint): void
    {
        switch ($acl->getType()->getValue()) {
            case Role::REGION_API:
                $this->violate($constraint->message, 'REGION', 'Api Access');
                break;
            case Role::COUNTRY_API:
                $this->violate($constraint->message, 'COUNTRY', 'Api Access');
                break;
            case Role::SITE_API:
                $this->violate($constraint->message, 'SITE', 'Api Access');
                break;
            case Role::REGION_IMPORT:
                $this->violate($constraint->message, 'REGION', 'Import Access');
                break;
            case Role::COUNTRY_IMPORT:
                $this->violate($constraint->message, 'COUNTRY', 'Import Access');
                break;
            case Role::SITE_IMPORT:
                $this->violate($constraint->message, 'SITE', 'Import Access');
                break;
        }
    }

    private function violate(string $message, string $type, string $option): void
    {
        $this->context
            ->buildViolation($message)
            ->setParameter('%type%', $type)
            ->setParameter('%option%', $option)
            ->atPath('type')
            ->addViolation();
    }
}
