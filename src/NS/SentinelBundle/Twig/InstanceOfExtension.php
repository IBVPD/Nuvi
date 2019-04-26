<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 08/08/18
 * Time: 10:25 AM
 */

namespace NS\SentinelBundle\Twig;

use Twig_Extension;
use Twig_SimpleFunction;

class InstanceOfExtension extends Twig_Extension
{
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('class_type', function ($value) { return is_object($value) ? get_class($value): null; }),
        ];
    }
}
