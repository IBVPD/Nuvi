<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 27/07/18
 * Time: 8:31 AM
 */

namespace NS\SentinelBundle\Entity\Listener;

use NS\SentinelBundle\Entity\Pneumonia\SiteLab;
use NS\UtilBundle\Form\Types\ArrayChoice;

class PneumoniaSiteLabListener extends BaseStatusListener
{
    public function getIncompleteField($case): ?string
    {
        if ($case instanceof SiteLab) {
            $regionCode = $case->getCaseFile()->getRegion()->getCode();
            foreach ($this->getMinimumRequiredFields($case, $regionCode) as $field) {
                $method = sprintf('get%s', $field);
                $value  = call_user_func([$case, $method]);

                if ($value === null || empty($value) || ($value instanceof ArrayChoice && $value->equal(ArrayChoice::NO_SELECTION))) {
                    return $field;
                }
            }
        }

        return null;
    }

    /**
     * @param SiteLab     $case
     * @param null|string $regionCode
     */
    protected function getMinimumRequiredFields($case, ?string $regionCode = null): array
    {
        $fields = [];
        if ($case->getCaseFile()->getBloodCollected()) {
            $fields += ['bloodId', 'bloodLabDate', 'bloodLabTime', 'bloodCultDone', 'bloodGramDone', 'bloodPcrDone'];
            if ($case->getBloodCultDone()) {
                $fields += ['bloodCultResult', 'bloodCultOther',
                    'bloodGramStain',
                    'bloodGramResult',
                    'bloodGramOther',
                    'bloodPcrResult',
                    'bloodPcrOther',];
            }
        }

//============
        $fields = ['bloodSecondId',
            'bloodSecondLabDate',
            'bloodSecondLabTime',
            'bloodSecondCultDone',
            'bloodSecondGramDone',
            'bloodSecondPcrDone',
            'bloodSecondCultResult',
            'bloodSecondCultOther',
            'bloodSecondGramStain',
            'bloodSecondGramResult',
            'bloodSecondGramOther',
            'bloodSecondPcrResult',
            'bloodSecondPcrOther',

//============
            'otherId',
            'otherLabDate',
            'otherLabTime',
            'otherCultDone',
            'otherCultResult',
            'otherCultOther',
            'otherTestDone',
            'otherTestResult',
            'otherTestOther',
//==================================
            'rlIsolBloodSent',
            'rlIsolBloodDate',
            'rlBrothSent',
            'rlBrothDate',
            'rlOtherSent',
            'rlOtherDate',

//=================================
// NL
            'nlIsolBloodSent',
            'nlIsolBloodDate',
            'nlBrothSent',
            'nlBrothDate',
            'nlOtherSent',
            'nlOtherDate',

//=================================
// PAHO
            'pleuralFluidCultureDone',
            'pleuralFluidCultureResult',
            'pleuralFluidCultureOther',
            'pleuralFluidGramDone',
            'pleuralFluidGramResult',
            'pleuralFluidGramResultOrganism',
            'pleuralFluidPcrDone',
            'pleuralFluidPcrResult',
            'pleuralFluidPcrOther',];

        return $fields;
    }
}
