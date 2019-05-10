<?php

namespace NS\SentinelBundle\Form\Pneumonia\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class CTValueTransformer implements DataTransformerInterface
{
    public function reverseTransform($value)
    {
        if (empty($value)) {
            return null;
        }

        if (isset($value['choice']) && $value['choice'] < 0) {
            return $value['choice'];
        }

        if (isset($value['number']) && $value['number'] >= 0) {
            return $value['number'];
        }

        return null;
    }

    public function transform($value)
    {
        if (is_numeric($value)) {
            if ($value < 0) {
                return ['choice' => (int) $value, 'number' => null];
            }

            if ($value >= 0) {
                return ['choice' => null, 'number' => $value];
            }
        }

        return ['choice' => null, 'number' => null];
    }
}
