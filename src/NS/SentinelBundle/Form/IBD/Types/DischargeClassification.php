<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Description of DischargeClassification
 *
 */
class DischargeClassification extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const CONFIRMED_HI      = 1;
    const CONFIRMED_SPN     = 2;
    const CONFIRMED_NM      = 3;
    const CONFIRMED_OTHER   = 4;
    const PROBABLE          = 5;
    const SUSPECT           = 6;
    const INCOMPLETE        = 7;
    const DISCARDED         = 8;
    const UNKNOWN           = 99;

    protected $values = array(
                            self::CONFIRMED_HI      => 'Lab-confirmed for HI',
                            self::CONFIRMED_SPN     => 'Lab-confirmed for Spn',
                            self::CONFIRMED_NM      => 'Lab-confirmed for Nm',
                            self::CONFIRMED_OTHER   => 'Lab-confirmed for other organism',
                            self::PROBABLE          => 'Probable',
                            self::SUSPECT           => 'Suspect',
                            self::INCOMPLETE        => 'Incomplete investigation',
                            self::DISCARDED         => 'Discarded case',
                            self::UNKNOWN           => 'Unknown',
                             );

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
            unset($this->values[self::SUSPECT]);
            unset($this->values[self::UNKNOWN]);
        }

        parent::configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'DischargeClassification';
    }
}
