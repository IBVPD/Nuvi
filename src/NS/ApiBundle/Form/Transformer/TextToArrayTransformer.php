<?php

namespace NS\ApiBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Description of TextToArrayTransformer
 *
 * @author gnat
 */
class TextToArrayTransformer implements DataTransformerInterface
{
    public function reverseTransform($value)
    {
        return explode(",", $value);
    }

    public function transform($value)
    {
        if(is_array($value) && !empty($value))
            return implode(",", $value);

        return null;
    }
}
