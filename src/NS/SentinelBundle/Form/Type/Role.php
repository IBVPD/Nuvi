<?php

namespace NS\SentinelBundle\Form\Type;
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

    protected $values = array(
                                self::REGION     => 'Region',
                                self::COUNTRY    => 'Country',
                                self::SITE       => 'Site',
                                self::LAB        => 'Lab',
                             );

    protected $rolemapping = array(
                                'ROLE_REGION'   => self::REGION,
                                'ROLE_COUNTRY'  => self::COUNTRY,
                                'ROLE_SITE'     => self::SITE,
                                'ROLE_LAB'      => self::LAB,
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
            case self::REGION;
                return array('ROLE_REGION');
                break;
            case self::COUNTRY;
                return array('ROLE_COUNTRY');
                break;
            case self::SITE;
                return array('ROLE_SITE');
                break;
            case self::LAB;
                return array('ROLE_LAB');
                break;

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
                break;
            case self::COUNTRY:
                return $class."\Country";
                break;
            case self::SITE:
                return $class."\SITE";
                break;
            case self::LAB:
                return $class."\Lab";
                break;
            default:
                return null;
        }
    }

    public function setSecurityContext(SecurityContext $context)
    {
        $this->_securityContext = $context;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'choices'     => $this->values,
            'empty_value' => 'Please Select...',
        ));
    }

    public function equal($to)
    {
        if(is_numeric($to))
            return ($to === $this->current);
        else if(is_string($to))
            return (array($to) == $this->getAsCredential());
        else if($to instanceof Role)
            return ($to->getValue () == $this->current);
        
        return false;
    }
}
