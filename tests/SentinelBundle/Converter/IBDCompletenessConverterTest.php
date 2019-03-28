<?php

namespace NS\SentinelBundle\Tests\Converter;

use NS\SentinelBundle\Converter\IBDCompletenessConverter;
use NS\SentinelBundle\Form\IBD\Types\CXRResult;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use NS\SentinelBundle\Form\Types\TripleChoice;

/**
 * Class IBDCompletenessConverterTest
 * @package NS\SentinelBundle\Tests\Converter
 */
class IBDCompletenessConverterTest extends TestCase
{
    public function testIBDFields(): void
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
        $this->assertInstanceOf(IBDCompletenessConverter::class, $converter);

        $output = $converter->__invoke($data);
        $this->assertInstanceOf(TripleChoice::class, $output['cxrDone']);
    }
}
