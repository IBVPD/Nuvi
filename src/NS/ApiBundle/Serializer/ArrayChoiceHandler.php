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
        return array(
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\VaccinationReceived',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\SampleType',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\IBDCaseResult',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\AlternateTripleChoice',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\TripleChoice',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\BinaxResult',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\CSFAppearance',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\CaseStatus',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\Diagnosis',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\Gender',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\LatResult',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\CXRResult',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\CXRAdditionalResult',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\CultureResult',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\Dehydration',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\DischargeClassification',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\DischargeDiagnosis',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\DischargeOutcome',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\ElisaKit',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\ElisaResult',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\FourDoses',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\GenotypeResultG',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\GenotypeResultP',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\GramStain',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\GramStainOrganism',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\HiSerotype',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\IsolateType',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\LatResult',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\MeningitisVaccinationReceived',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\MeningitisVaccinationType',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\NmSerogroup',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\OtherSpecimen',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\PCRResult',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\PCVType',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\PathogenIdentifier',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\Rehydration',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\RotavirusDischargeOutcome',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\RotavirusVaccionationReceived',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\RotavirusVaccinationType',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\SerotypeIdentifier',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\SpnSerotype',
                'method'    => 'serializeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'NS\SentinelBundle\Form\Types\ThreeDoses',
                'method'    => 'serializeToJson',
            ),
        );
    }
}
