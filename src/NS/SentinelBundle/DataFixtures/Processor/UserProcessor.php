<?php

namespace NS\SentinelBundle\DataFixtures\Processor;

use Nelmio\Alice\ProcessorInterface;
use NS\SentinelBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

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
        if ($object instanceof User) {
            if (!$this->encoder) {
                $this->encoder = $this->encoderFactory->getEncoder($object);
            }

            $object->resetSalt();
            $object->setPassword($this->encoder->encodePassword($object->getPlainPassword(), $object->getSalt()));
        }
    }
}
