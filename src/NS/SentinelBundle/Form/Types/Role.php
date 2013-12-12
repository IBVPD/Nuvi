<?php

namespace NS\SentinelBundle\Form\Types;
use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Description of Role
 *
 * @author gnat
 */
class Role extends ArrayChoice
{
    private $_securityContext;

    const REGION  = 1;
    const COUNTRY = 2;
    const SITE    = 3;
    const LAB     = 4;
    const RRL_LAB = 5;

    protected $values = array(
                                self::REGION     => 'Region',
                                self::COUNTRY    => 'Country',
                                self::SITE       => 'Site',
                                self::LAB        => 'Lab',
                                self::RRL_LAB    => 'RRL',
                             );

    protected $rolemapping = array(
                                'ROLE_REGION'   => self::REGION,
                                'ROLE_COUNTRY'  => self::COUNTRY,
                                'ROLE_SITE'     => self::SITE,
                                'ROLE_LAB'      => self::LAB,
                                'ROLE_RRL_LAB'  => self::RRL_LAB,
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
            case self::RRL_LAB:
                return array('ROLE_RRL_LAB');
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
                return $class."\Region";
            case self::COUNTRY:
                return $class."\Country";
            case self::RRL_LAB:
            case self::LAB:
            case self::SITE:
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
