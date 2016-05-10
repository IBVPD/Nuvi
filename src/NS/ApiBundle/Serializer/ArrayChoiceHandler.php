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
        $types = array(
            'NS\SentinelBundle\Form\Types\VaccinationReceived',
            'NS\SentinelBundle\Form\Types\SampleType',
            'NS\SentinelBundle\Form\Types\TripleChoice',
            'NS\SentinelBundle\Form\Types\CaseStatus',
            'NS\SentinelBundle\Form\Types\Gender',
            'NS\SentinelBundle\Form\Types\ThreeDoses',
            'NS\SentinelBundle\Form\Types\FourDoses',

            'NS\SentinelBundle\Form\IBD\Types\BinaxResult',
            'NS\SentinelBundle\Form\IBD\Types\CaseResult',
            'NS\SentinelBundle\Form\IBD\Types\CSFAppearance',

            'NS\SentinelBundle\Form\IBD\Types\CultureResult',
            'NS\SentinelBundle\Form\IBD\Types\CXRAdditionalResult',
            'NS\SentinelBundle\Form\IBD\Types\CXRResult',
            'NS\SentinelBundle\Form\IBD\Types\Diagnosis',
            'NS\SentinelBundle\Form\IBD\Types\DischargeClassification',
            'NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis',
            'NS\SentinelBundle\Form\IBD\Types\DischargeOutcome',
            'NS\SentinelBundle\Form\IBD\Types\FinalResult',
            'NS\SentinelBundle\Form\IBD\Types\GramStain',
            'NS\SentinelBundle\Form\IBD\Types\GramStainResult',
            'NS\SentinelBundle\Form\IBD\Types\HiSerotype',

            'NS\SentinelBundle\Form\IBD\Types\IntenseSupport', // not likely needed

            'NS\SentinelBundle\Form\IBD\Types\IsolateType',
            'NS\SentinelBundle\Form\IBD\Types\IsolateViable',
            'NS\SentinelBundle\Form\IBD\Types\LatResult',
            'NS\SentinelBundle\Form\IBD\Types\NmSerogroup',
            'NS\SentinelBundle\Form\IBD\Types\OtherSpecimen',
            'NS\SentinelBundle\Form\IBD\Types\VaccinationType',
            'NS\SentinelBundle\Form\IBD\Types\PathogenIdentifier',
            'NS\SentinelBundle\Form\IBD\Types\PCRResult',
            'NS\SentinelBundle\Form\IBD\Types\PCVType',
            'NS\SentinelBundle\Form\IBD\Types\SampleType',
            'NS\SentinelBundle\Form\IBD\Types\SerotypeIdentifier',
            'NS\SentinelBundle\Form\IBD\Types\SpnSerotype',
            'NS\SentinelBundle\Form\IBD\Types\VaccinationType',

            'NS\SentinelBundle\Form\RotaVirus\Types\Dehydration',
            'NS\SentinelBundle\Form\RotaVirus\Types\DischargeClassification',
            'NS\SentinelBundle\Form\RotaVirus\Types\DischargeOutcome',
            'NS\SentinelBundle\Form\RotaVirus\Types\ElisaKit',
            'NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult',
            'NS\SentinelBundle\Form\RotaVirus\Types\Rehydration',
            'NS\SentinelBundle\Form\RotaVirus\Types\VaccinationType',
            'NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG',
            'NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP',
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
