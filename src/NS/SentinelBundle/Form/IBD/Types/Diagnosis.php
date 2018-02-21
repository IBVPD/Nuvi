<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Description of Diagnosis
 *
 * @author gnat
 */
class Diagnosis extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const SUSPECTED_MENINGITIS       = 1;
    const SUSPECTED_PNEUMONIA        = 2;
    const SUSPECTED_SEVERE_PNEUMONIA = 3;
    const SUSPECTED_SEPSIS           = 4;
    const MULTIPLE                   = 5;
    const OTHER                      = 6;
    const UNKNOWN                    = 99;

    protected $values = [
        self::SUSPECTED_MENINGITIS => 'Suspected meningitis',
        self::SUSPECTED_PNEUMONIA => 'Suspected pneumonia',
        self::SUSPECTED_SEVERE_PNEUMONIA => 'Suspected severe pneumonia',
        self::SUSPECTED_SEPSIS => 'Suspected sepsis',
        self::MULTIPLE => 'Multiple (i.e. suspected meningitis and/or pneumonia and/or sepsis)',
        self::OTHER => 'Other',
        self::UNKNOWN => 'Unknown'
    ];

    /** @var AuthorizationCheckerInterface */
    private $authChecker;

    /**
     * @param AuthorizationCheckerInterface $authChecker
     */
    public function setAuthorizationChecker($authChecker)
    {
        $this->authChecker = $authChecker;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        if ($this->authChecker->isGranted('ROLE_AMR')) {
            unset($this->values[self::SUSPECTED_SEVERE_PNEUMONIA]);
            unset($this->values[self::UNKNOWN]);
            unset($this->values[self::OTHER]);
            unset($this->values[self::SUSPECTED_SEPSIS]);
        }

        parent::configureOptions($resolver);
    }
}
