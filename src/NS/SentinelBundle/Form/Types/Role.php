<?php

namespace NS\SentinelBundle\Form\Types;

use \Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Security\Core\SecurityContext;
use \JMS\TranslationBundle\Translation\TranslationContainerInterface;
use \NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of Role
 *
 * @author gnat
 */
class Role extends TranslatableArrayChoice implements TranslationContainerInterface
{
    private $securityContext;

    const REGION         = 1;
    const COUNTRY        = 2;
    const SITE           = 3;
    const LAB            = 4;
    const RRL_LAB        = 5;
    const NL_LAB         = 6;
    const REGION_API     = 10;
    const COUNTRY_API    = 11;
    const SITE_API       = 12;
    const REGION_IMPORT  = 20;
    const COUNTRY_IMPORT = 21;
    const SITE_IMPORT    = 22;

    protected $values = array(
        self::REGION         => 'Region',
        self::COUNTRY        => 'Country',
        self::SITE           => 'Site',
        self::LAB            => 'Lab',
        self::RRL_LAB        => 'RRL',
        self::NL_LAB         => 'NL',
        self::REGION_API     => 'Region API',
        self::COUNTRY_API    => 'Country API',
        self::SITE_API       => 'Site API',
        self::REGION_IMPORT  => 'Region Import/Export',
        self::COUNTRY_IMPORT => 'Country Import/Export',
        self::SITE_IMPORT    => 'Site Import/Export',
                             );

    protected $rolemapping = array(
        'ROLE_REGION'         => self::REGION,
        'ROLE_COUNTRY'        => self::COUNTRY,
        'ROLE_SITE'           => self::SITE,
        'ROLE_LAB'            => self::LAB,
        'ROLE_RRL_LAB'        => self::RRL_LAB,
        'ROLE_NL_LAB'         => self::NL_LAB,
        'ROLE_REGION_API'     => self::REGION_API,
        'ROLE_COUNTRY_API'    => self::COUNTRY_API,
        'ROLE_SITE_API'       => self::SITE_API,
        'ROLE_REGION_IMPORT'  => self::REGION_IMPORT,
        'ROLE_COUNTRY_IMPORT' => self::COUNTRY_IMPORT,
        'ROLE_SITE_IMPORT'    => self::SITE_IMPORT,
        );

    /**
     *
     * @param string $value
     * @return Role
     * @throws \UnexpectedValueException
     */
    public function __construct($value = null)
    {
        if (is_string($value) && strstr($value, 'ROLE_') !== false) {
            if (isset($this->rolemapping[$value])) {
                $value = $this->rolemapping[$value];
            }
            else {
                throw new \UnexpectedValueException("$value is not a valid role mapping");
            }
        }

        return parent::__construct($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'role';
    }

    /**
     * @return array
     */
    public function getAsCredential()
    {
        switch($this->current)
        {
            case self::REGION:
                return array('ROLE_REGION');
            case self::COUNTRY:
                return array('ROLE_COUNTRY');
            case self::SITE:
                return array('ROLE_SITE');
            case self::LAB:
                return array('ROLE_LAB');
            case self::RRL_LAB:
                return array('ROLE_RRL_LAB');
            case self::NL_LAB:
                return array('ROLE_NL_LAB');
            case self::REGION_API:
                return array('ROLE_REGION_API', 'ROLE_CAN_CREATE_CASE','ROLE_CAN_CREATE_LAB','ROLE_CAN_CREATE_NL_LAB');
            case self::COUNTRY_API:
                return array('ROLE_COUNTRY_API','ROLE_CAN_CREATE_CASE','ROLE_CAN_CREATE_LAB','ROLE_CAN_CREATE_NL_LAB');
            case self::SITE_API:
                return array('ROLE_SITE_API','ROLE_CAN_CREATE_CASE','ROLE_CAN_CREATE_LAB');
            case self::REGION_IMPORT:
                return array('ROLE_REGION_IMPORT');
            case self::COUNTRY_IMPORT:
                return array('ROLE_COUNTRY_IMPORT');
            case self::SITE_IMPORT:
                return array('ROLE_SITE_IMPORT');
            default:
                return null;
        }
    }

    /**
     * @return string
     */
    public function getClassMatch()
    {
        $class = 'NS\SentinelBundle\Entity';
        switch($this->current)
        {
            case self::REGION:
            case self::REGION_API:
            case self::REGION_IMPORT:
                return $class."\Region";
            case self::COUNTRY:
            case self::COUNTRY_API:
            case self::COUNTRY_IMPORT:
            case self::NL_LAB:
            case self::RRL_LAB:
                return $class . "\Country";
            case self::LAB:
            case self::SITE:
            case self::SITE_API:
            case self::SITE_IMPORT:
                return $class."\Site";
            default:
                return null;
        }
    }

    /**
     * @param SecurityContext $context
     */
    public function setSecurityContext(SecurityContext $context)
    {
        $this->securityContext = $context;
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
            if (isset($this->rolemapping[$role->getRole()])) {
                if ($highest == null) {
                    $highest = $this->rolemapping[$role->getRole()];
                }
                else if ($highest > $this->rolemapping[$role->getRole()]) { //highest is actually 1...
                    $highest = $this->rolemapping[$role->getRole()];
                }
            }
        }

        return $highest;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $highest = $this->getHighest($this->securityContext->getToken()->getRoles());

        if (!is_null($highest) && $highest != self::REGION) {
            $values = $this->values;
            foreach (array_keys($values) as $key) {
                if ($key < $highest) {
                    unset($values[$key]);
                }
            }

            $resolver->setDefaults(array(
                'choices'     => $values,
                'empty_value' => 'Please Select...',
            ));
        }
        else {
            $resolver->setDefaults(array(
                'choices'     => $this->values,
                'empty_value' => 'Please Select...',
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function equal($compared)
    {
        if (is_numeric($compared)) {
            return ($compared == $this->current);
        }
        elseif (is_string($compared)) {
            return (array($compared) == $this->getAsCredential());
        }
        elseif ($compared instanceof Role) {
            return ($compared->getValue() == $this->current);
        }

        return false;
    }
}
