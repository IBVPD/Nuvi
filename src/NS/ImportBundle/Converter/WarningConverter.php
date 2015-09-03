<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 03/09/15
 * Time: 10:03 AM
 */

namespace NS\ImportBundle\Converter;


use Ddeboer\DataImport\Step\ConverterStep;
use NS\UtilBundle\Form\Types\ArrayChoice;

class WarningConverter extends ConverterStep
{
    /**
     * @inheritDoc
     */
    public function __invoke(&$item)
    {
        foreach($item as $key=>$value) {
            if($value instanceof ArrayChoice) {
                if($value->equal(ArrayChoice::OUT_OF_RANGE)) {
                    $item['warning'] = true;
                    break;
                }
            }
        }
    }
}