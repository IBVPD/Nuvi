<?php

namespace NS\ImportBundle\Filter;

/**
 * Description of LinkerFilterFactory
 *
 * @author gnat
 */
class LinkerFilterFactory extends AbstractFilterFactory
{
    /**
     * @param array $services
     */
    public function __construct(array $services)
    {
        $classes = array(
            'NS\SentinelBundle\Entity\IBD'       => array(),
            'NS\SentinelBundle\Entity\RotaVirus' => array(),
        );

        foreach ($services as $service) {
            foreach ($classes as $class => &$srv) {
                if ($service->supportsClass($class)) {
                    $srv[] = $service;
                }
            }
        }

        $this->setTypelist($classes);
    }

    /**
     * @param string $className
     * @return null|array
     */
    public function createFilter($className)
    {
        if (!$className) {
            return null;
        }

        if (!array_key_exists($className, $this->typelist)) {
            return null;
        }

        return $this->typelist[$className];
    }
}
