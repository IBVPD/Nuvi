<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use \NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Description of CultureResult
 *
 */
class CultureResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const NEGATIVE      = 0;
    const SPN           = 1;
    const HI            = 2;
    const NM            = 3;
    const OTHER         = 4;
    const CONTAMINANT   = 5;
    const UNKNOWN       = 99;

    protected $values = array(
                                self::NEGATIVE      => 'Negative',
                                self::SPN           => 'Spn',
                                self::HI            => 'Hi',
                                self::NM            => 'Nm',
                                self::OTHER         => 'Other',
                                self::CONTAMINANT   => 'Contaminant',
                                self::UNKNOWN       => 'Unknown',
                             );

    /** @var AuthorizationCheckerInterface */
    private $authChecker;

    /**
     * @param AuthorizationCheckerInterface $authChecker
     */
    public function setAuthorizationChecker(AuthorizationCheckerInterface $authChecker)
    {
        $this->authChecker = $authChecker;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        if ($this->authChecker->isGranted('ROLE_AMR')) {
            unset($this->values[self::UNKNOWN]);
        }

        parent::configureOptions($resolver);
    }
}
