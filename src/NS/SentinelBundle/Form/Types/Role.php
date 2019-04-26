<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Description of Role
 *
 * @author gnat
 */
class Role extends TranslatableArrayChoice implements TranslationContainerInterface
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    const REGION = 1;
    const COUNTRY = 2;
    const SITE = 3;
    const LAB = 4;
    const RRL_LAB = 5;
    const NL_LAB = 6;

    // These are all deprecated and will be removed in a future version
    const REGION_API = 10;
    const COUNTRY_API = 11;
    const SITE_API = 12;

    const REGION_IMPORT = 20;
    const COUNTRY_IMPORT = 21;
    const SITE_IMPORT = 22;

    protected $values = [
        self::REGION => 'Region',
        self::COUNTRY => 'Country',
        self::SITE => 'Site',
        self::LAB => 'Lab',
        self::RRL_LAB => 'RRL',
        self::NL_LAB => 'NL',
    ];

    protected $roleMap = [
        'ROLE_REGION' => self::REGION,
        'ROLE_COUNTRY' => self::COUNTRY,
        'ROLE_SITE' => self::SITE,
        'ROLE_LAB' => self::LAB,
        'ROLE_RRL_LAB' => self::RRL_LAB,
        'ROLE_NL_LAB' => self::NL_LAB,
    ];

    /**
     * @param string $value
     */
    public function __construct($value = null)
    {
        if (is_string($value) && strstr($value, 'ROLE_') !== false) {
            if (isset($this->roleMap[$value])) {
                $value = $this->roleMap[$value];
            } else {
                throw new \UnexpectedValueException("$value is not a valid role mapping");
            }
        }

        parent::__construct($value);
    }

    /**
     * @return array
     */
    public function getAsCredential()
    {
        switch ($this->current) {
            case self::REGION:
                return ['ROLE_REGION'];
            case self::COUNTRY:
                return ['ROLE_COUNTRY'];
            case self::SITE:
                return ['ROLE_SITE'];
            case self::LAB:
                return ['ROLE_LAB'];
            case self::RRL_LAB:
                return ['ROLE_RRL_LAB'];
            case self::NL_LAB:
                return ['ROLE_NL_LAB'];
            default:
                return [];
        }
    }

    /**
     * @return string
     */
    public function getClassMatch()
    {
        $class = 'NS\SentinelBundle\Entity';
        switch ($this->current) {
            case self::REGION:
                return $class . '\Region';
            case self::COUNTRY:
            case self::NL_LAB:
            case self::RRL_LAB:
                return $class . '\Country';
            case self::LAB:
            case self::SITE:
                return $class . '\Site';
            default:
                return null;
        }
    }

    /**
     *
     * @param array $roles
     * @return integer
     */
    public function getHighest(array $roles)
    {
        $highest = null;

        foreach ($roles as $role) {
            if (isset($this->roleMap[$role->getRole()])) {
                if ($highest === null) {
                    $highest = $this->roleMap[$role->getRole()];
                } elseif ($highest > $this->roleMap[$role->getRole()]) { //highest is actually 1...
                    $highest = $this->roleMap[$role->getRole()];
                }
            }
        }

        return $highest;
    }

    /**
     * @param TokenStorageInterface $tokenStorage
     * @return Role
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $highest = $this->getHighest($this->tokenStorage->getToken()->getRoles());

        if (!is_null($highest) && $highest != self::REGION) {
            $values = $this->values;
            foreach (array_keys($values) as $key) {
                if ($key < $highest) {
                    unset($values[$key]);
                }
            }

            $resolver->setDefaults([
                'choices' => array_flip($values),
                'placeholder' => 'Please Select...',
            ]);
        } else {
            $resolver->setDefaults([
                'choices' => array_flip($this->values),
                'placeholder' => 'Please Select...',
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function equal($compared)
    {
        if (is_numeric($compared)) {
            return ($compared == $this->current);
        } elseif (is_string($compared)) {
            return ([$compared] == $this->getAsCredential());
        } elseif ($compared instanceof Role) {
            return ($compared->getValue() == $this->current);
        }

        return false;
    }
}
