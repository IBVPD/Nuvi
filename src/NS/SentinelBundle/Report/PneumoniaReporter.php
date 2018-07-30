<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 21/06/18
 * Time: 12:40 PM
 */

namespace NS\SentinelBundle\Report;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class PneumoniaReporter extends IBDReporter
{
    public function getFieldPopulation(Request $request, FormInterface $form, $redirectRoute)
    {
        throw new \RuntimeException("This report doesn't make sense for pneumonia");
    }
}
