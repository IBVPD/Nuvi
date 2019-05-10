<?php

namespace NS\SentinelBundle\Validators\Cache;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CachedValidations
{
    /** @var AbstractAdapter */
    private $cache;

    /** @var ValidatorInterface */
    private $validator;

    public function __construct(AbstractAdapter $cache, ValidatorInterface $validator)
    {
        $this->cache     = $cache;
        $this->validator = $validator;
    }

    public function collect(string $key, ?string $class = null): array
    {
        $item = $this->cache->getItem($key);
        if (!$item->isHit()) {
            return [];
        }

        $results = $item->get();

        if (!$class) {
            return $results;
        }

        return $results[$class] ?? [];
    }

    public function validate(string $key, $object, $groups, bool $recompute = false)
    {
        $item        = $this->cache->getItem($key);
        $results     = $item->isHit() ? $item->get() : [];
        $subKey      = get_class($object);
        $haveResults = isset($results[$subKey]);

        if (!$haveResults) {
            $results[$subKey] = $this->doValidation($object, $groups);
            $item->set($results);
            $this->cache->save($item);
        }

        if ($recompute === true) {
            if ($haveResults) {
                $results[$subKey] = $this->doValidation($object, $groups);
            }

            $item->set($results);
            $this->cache->save($item);
        }

        return $results[$subKey];
    }

    public function clear(string $key): void
    {
        try {
            $this->cache->deleteItem($key);
        } catch (InvalidArgumentException $e) {
        }
    }

    private function doValidation($object, $groups): array
    {
        $violationList = $this->validator->validate($object, null, $groups);
        if ($violationList->count() === 0) {
            return [];
        }

        $results = [];

        foreach ($violationList as $violation) {
            $results[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $results;
    }
}
