<?php

namespace NS\SentinelBundle\Form\Types;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of Role
 *
 * @author gnat
 */
class Role extends TranslatableArrayChoice implements TranslationContainerInterface
{
    private $_securityContext;

    const REGION  = 1;
    const COUNTRY = 2;
    const SITE    = 3;
    const LAB     = 4;
    const REGION_API  = 10;
    const COUNTRY_API = 11;
    const SITE_API    = 12;

    protected $values = array(
                                self::REGION     => 'Region',
                                self::COUNTRY    => 'Country',
                                self::SITE       => 'Site',
                                self::LAB        => 'Lab',
                                self::REGION_API  => 'Region API',
                                self::COUNTRY_API => 'Country API',
                                self::SITE_API    => 'Site API',
                             );

    protected $rolemapping = array(
                                'ROLE_REGION'   => self::REGION,
                                'ROLE_COUNTRY'  => self::COUNTRY,
                                'ROLE_SITE'     => self::SITE,
                                'ROLE_LAB'      => self::LAB,
                                'ROLE_REGION_API'  => self::REGION_API,
                                'ROLE_COUNTRY_API' => self::COUNTRY_API,
                                'ROLE_SITE_API'    => self::SITE_API,
                              );
    
    public function __construct($value = null)
    {
        if(!is_null($value) && is_string($value) && strstr($value,'ROLE_'))
        {
            if(isset($this->rolemapping[$value]))
                $value = $this->rolemapping[$value];
            else
                throw new \UnexpectedValueException("$value is not a valid role mapping");
        }

        return parent::__construct($value);
    }

    public function getName()
    {
        return 'role';
    }

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
            case self::REGION_API:
                return array('ROLE_REGION_API');
            case self::COUNTRY_API:
                return array('ROLE_COUNTRY_API');
            case self::SITE_API:
                return array('ROLE_SITE_API');
            default:
                return null;
        }
    }

    public function getClassMatch()
    {
        $class = 'NS\SentinelBundle\Entity';
        switch($this->current)
        {
            case self::REGION:
            case self::REGION_API:
                return $class."\Region";
            case self::COUNTRY:
            case self::COUNTRY_API:
                return $class."\Country";
            case self::LAB:
            case self::SITE:
            case self::SITE_API:
                return $class."\Site";
            default:
                return null;
        }
    }

    public function setSecurityContext(SecurityContext $context)
    {
        $this->_securityContext = $context;
    }

    public function getHighest(array $roles)
    {
        $highest = null;

        foreach($roles as $r)
        {
            if(isset($this->rolemapping[$r->getRole()]))
            {
                if($highest == null)
                    $highest = $this->rolemapping[$r->getRole()];
                else if ($highest > $this->rolemapping[$r->getRole()])//highest is actually 1...
                    $highest = $this->rolemapping[$r->getRole()];
            }
        }

        return $highest;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $highest = $this->getHighest($this->_securityContext->getToken()->getRoles());

        if(!is_null($highest) && $highest != self::REGION)
        {
            $values = $this->values;
            foreach($values as $k=>$v)
            {
                if($k < $highest)
                    unset($values[$k]);
            }

            $resolver->setDefaults(array(
                'choices'     => $values,
                'empty_value' => 'Please Select...',
            ));
        }
        else
            $resolver->setDefaults(array(
                'choices'     => $this->values,
                'empty_value' => 'Please Select...',
            ));
    }

    public function equal($to)
    {
        if(is_numeric($to))
            return ($to == $this->current);
        else if(is_string($to))
            return (array($to) == $this->getAsCredential());
        else if($to instanceof Role)
            return ($to->getValue () == $this->current);

        return false;
    }
}
