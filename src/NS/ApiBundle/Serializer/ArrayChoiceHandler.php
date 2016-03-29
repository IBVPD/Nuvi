<?php

namespace NS\ApiBundle\Serializer;

use \JMS\Serializer\GraphNavigator;
use \JMS\Serializer\Handler\SubscribingHandlerInterface;
use \JMS\Serializer\JsonSerializationVisitor;
use \JMS\Serializer\SerializationContext;

/**
 * Description of ArrayChoiceHandler
 *
 * @author gnat
 */
class ArrayChoiceHandler implements SubscribingHandlerInterface
{
    /**
     * @param JsonSerializationVisitor $visitor
     * @param mixed $data
     * @param array $type
     * @param SerializationContext $context
     * @return integer
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function serializeToJson(JsonSerializationVisitor $visitor, $data, array $type, SerializationContext $context)
    {
        return (int) $data->getValue();
    }

    /**
     * @return array
     */
    public static function getSubscribingMethods()
    {
        $ret = array();
        $types = array('NS\SentinelBundle\Form\Types\VaccinationReceived',
            'NS\SentinelBundle\Form\Types\SampleType',
            'NS\SentinelBundle\Form\Types\IBDCaseResult',
            'NS\SentinelBundle\Form\Types\IsolateViable',
            'NS\SentinelBundle\Form\Types\TripleChoice',
            'NS\SentinelBundle\Form\Types\BinaxResult',
            'NS\SentinelBundle\Form\Types\CSFAppearance',
            'NS\SentinelBundle\Form\Types\CaseStatus',
            'NS\SentinelBundle\Form\Types\Diagnosis',
            'NS\SentinelBundle\Form\Types\Gender',
            'NS\SentinelBundle\Form\Types\LatResult',
            'NS\SentinelBundle\Form\Types\CXRResult',
            'NS\SentinelBundle\Form\Types\CXRAdditionalResult',
            'NS\SentinelBundle\Form\Types\CultureResult',
            'NS\SentinelBundle\Form\Types\Dehydration',
            'NS\SentinelBundle\Form\Types\DischargeClassification',
            'NS\SentinelBundle\Form\Types\DischargeDiagnosis',
            'NS\SentinelBundle\Form\Types\DischargeOutcome',
            'NS\SentinelBundle\Form\Types\ElisaKit',
            'NS\SentinelBundle\Form\Types\ElisaResult',
            'NS\SentinelBundle\Form\Types\FourDoses',
            'NS\SentinelBundle\Form\Types\GenotypeResultG',
            'NS\SentinelBundle\Form\Types\GenotypeResultP',
            'NS\SentinelBundle\Form\Types\GramStain',
            'NS\SentinelBundle\Form\Types\GramStainResult',
            'NS\SentinelBundle\Form\Types\HiSerotype',
            'NS\SentinelBundle\Form\Types\IsolateType',
            'NS\SentinelBundle\Form\Types\LatResult',
            'NS\SentinelBundle\Form\Types\MeningitisVaccinationReceived',
            'NS\SentinelBundle\Form\Types\MeningitisVaccinationType',
            'NS\SentinelBundle\Form\Types\NmSerogroup',
            'NS\SentinelBundle\Form\Types\OtherSpecimen',
            'NS\SentinelBundle\Form\Types\PCRResult',
            'NS\SentinelBundle\Form\Types\PCVType',
            'NS\SentinelBundle\Form\Types\PathogenIdentifier',
            'NS\SentinelBundle\Form\Types\Rehydration',
            'NS\SentinelBundle\Form\Types\RotavirusDischargeOutcome',
            'NS\SentinelBundle\Form\Types\RotavirusVaccionationReceived',
            'NS\SentinelBundle\Form\Types\RotavirusVaccinationType',
            'NS\SentinelBundle\Form\Types\SerotypeIdentifier',
            'NS\SentinelBundle\Form\Types\SpnSerotype',
            'NS\SentinelBundle\Form\Types\ThreeDoses',
        );

        foreach ($types as $type) {
            $ret[] = array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => $type,
                'method'    => 'serializeToJson',
            );
        }

        return $ret;
    }
}
