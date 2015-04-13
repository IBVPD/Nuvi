<?php

namespace NS\SentinelBundle\Form\Transformer;

/**
 * Description of CTValueTransformer
 *
 * @author gnat
 */
class CTValueTransformer implements \Symfony\Component\Form\DataTransformerInterface
{

    public function reverseTransform($value)
    {
        if(empty($value)) {
            return null;
        }

        if(isset($value['choice']) && $value['choice'] < 0)
            return $value['choice'];

        if(isset($value['number']) && $value['number'] >= 0)
            return $value['number'];

        return null;
    }

    public function transform($value)
    {
        if ($value < 0) {
            return array('choice' => $value, 'number' => null);
        }
        else if ($value >= 0) {
            return array('choice' => null, 'number' => $value);
        }

        return array('choice' => null, 'number' => null);
    }

}
