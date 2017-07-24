<?php

namespace NS\SentinelBundle\Tests\Converter;

use NS\SentinelBundle\Converter\IBDCompletenessConverter;
use NS\SentinelBundle\Form\IBD\Types\CXRResult;
use Symfony\Component\Validator\Validation;

/**
 * Class IBDCompletenessConverterTest
 * @package NS\SentinelBundle\Tests\Converter
 */
class IBDCompletenessConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testIBDFields()
    {
        $config = [
            'case'=> [
                ['resultField' => 'cxrResult', 'tripleChoiceField' => 'cxrDone',]
            ]
        ];

        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();

        $converter = new IBDCompletenessConverter($validator, $config);

        $data = [
            'cxrDone' => null,
            'cxrResult' => new CXRResult(CXRResult::CONSISTENT),
        ];
        $this->assertInstanceOf('NS\SentinelBundle\Converter\IBDCompletenessConverter', $converter);

        $output = $converter->__invoke($data);
        $this->assertInstanceOf('NS\SentinelBundle\Form\Types\TripleChoice', $output['cxrDone']);
    }
}
