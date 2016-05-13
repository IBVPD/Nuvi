<?php

namespace NS\SentinelBundle\Validators;

use NS\SentinelBundle\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Description of UserAclValidator
 *
 * @author gnat
 */
class UserAclValidator extends ConstraintValidator
{
    private $rolesToCheck = array(
        'ROLE_COUNTRY',
        'ROLE_SITE',
        'ROLE_LAB',
        'ROLE_NL_LAB',
        'ROLE_RRL_LAB',
        'ROLE_REGION_API',
        'ROLE_COUNTRY_API',
        'ROLE_SITE_API',
        'ROLE_REGION_IMPORT',
        'ROLE_COUNTRY_IMPORT',
        'ROLE_SITE_IMPORT'
    );

    /**
     * @param User $user
     * @param Constraint $constraint
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validate($user, Constraint $constraint)
    {
        $roles = $user->getRoles();
        if (in_array('ROLE_RRL_LAB', $roles) && !$user->hasReferenceLab()) {
            $this->context->buildViolation('The user is designated as able to create reference lab records but no reference lab has been linked')->atPath('referenceLab')->addViolation();
        }

        if (count($roles) >= 2 && $user->isAdmin()) {
            foreach($this->rolesToCheck as $role) {
                if(in_array($role, $roles)) {
                    $this->context->buildViolation('Only users with regional or no roles are allowed to be administrators')->atPath('admin')->addViolation();
                    return;
                }
            }
        }
    }
}
