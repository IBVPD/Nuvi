<?php

namespace NS\SentinelBundle\DataFixtures\Alice;

use \Nelmio\Alice\ProcessorInterface;
use \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Description of UserProcessor
 *
 * @author gnat
 */
class UserProcessor implements ProcessorInterface
{
    private $encoderFactory;

    private $encoder;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function postProcess($object)
    {
    }

    public function preProcess($object)
    {
        if ($object instanceof \NS\SentinelBundle\Entity\User)
        {
            if (!$this->encoder)
                $this->encoder = $this->encoderFactory->getEncoder($object);

            $object->resetSalt();
            $object->setPassword($this->encoder->encodePassword($object->getPlainPassword(), $object->getSalt()));
        }
    }
}
