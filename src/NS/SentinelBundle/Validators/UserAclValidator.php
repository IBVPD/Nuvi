<?php

namespace NS\SentinelBundle\Validators;

use NS\SentinelBundle\Entity\User;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Description of UserAclValidator
 *
 * @author gnat
 */
class UserAclValidator extends ConstraintValidator
{
    protected $roleMap = [
//        'ROLE_REGION',
//        'ROLE_COUNTRY',
        'ROLE_SITE',
        'ROLE_LAB',
        'ROLE_RRL_LAB',
        'ROLE_NL_LAB',
    ];

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authChecker;

    /**
     * UserAclValidator constructor.
     * @param AuthorizationCheckerInterface $authChecker
     */
    public function __construct(AuthorizationCheckerInterface $authChecker)
    {
        $this->authChecker = $authChecker;
    }

    /**
     * @param User $user
     * @param Constraint $constraint
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validate($user, Constraint $constraint)
    {
        $userRoles = $user->getRoles();
        if (in_array('ROLE_RRL_LAB', $userRoles) && !$user->hasReferenceLab()) {
            $this->context->buildViolation('The user is designated as able to create reference lab records but no reference lab has been linked')->atPath('referenceLab')->addViolation();
        }

        if ($user->isAdmin()) {
            // Only allow Region/Country users as administrators
            if (!empty(array_intersect($this->roleMap, $userRoles))) {
                $this->context->buildViolation("Only users with regional or no roles are allowed to be administrators")->atPath('admin')->addViolation();
            }

            // Don't allow a non-super admin to create a super admin
            if (!$this->authChecker->isGranted('ROLE_SUPER_ADMIN') && in_array('ROLE_SUPER_ADMIN', $userRoles)) {
                $this->context->buildViolation('Only super administrators are allowed to create other super admins')->atPath('admin')->addViolation();
            }
        }

        //This should technically never be possible due to the role->getHighest but lets be safe regardless
        if ($this->authChecker->isGranted('ROLE_SONATA_COUNTRY_ADMIN') && in_array('ROLE_REGION', $userRoles)) {
            $this->context->buildViolation('You may not create regional users')->addViolation();
        }
    }
}
