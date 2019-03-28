<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 14/02/18
 * Time: 10:24 AM
 */

namespace NS\SentinelBundle\Tests\Form\IBD\Types;

use NS\SentinelBundle\Form\IBD\Types\Diagnosis;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class DiagnosisTest extends TypeTestCase
{
    private $defaultChoices = [];

    protected function getExtensions()
    {
        $admDiagnosis = new Diagnosis();
        $this->defaultChoices = $admDiagnosis->getValues();
        return [new PreloadedExtension([$admDiagnosis], [])];
    }

    public function testNormalOptionSet()
    {
        $form = $this->factory->create(Diagnosis::class);
        $choices = $form->getConfig()->getOption('choices');
        $this->assertNotEmpty($choices);
        $this->assertEquals(array_flip($this->defaultChoices), $choices);
    }

    public function testAmrOptions()
    {
        $form = $this->factory->create(Diagnosis::class,null,[
            'exclude_choices' => [ Diagnosis::SUSPECTED_SEVERE_PNEUMONIA, Diagnosis::UNKNOWN, Diagnosis::OTHER, Diagnosis::SUSPECTED_SEPSIS,]
        ]);
        $choices = $form->getConfig()->getOption('choices');

        $this->assertNotEmpty($choices);
        unset($this->defaultChoices[Diagnosis::SUSPECTED_SEVERE_PNEUMONIA], $this->defaultChoices[Diagnosis::UNKNOWN], $this->defaultChoices[Diagnosis::OTHER], $this->defaultChoices[Diagnosis::SUSPECTED_SEPSIS]);
        $this->assertEquals(array_flip($this->defaultChoices), $choices);
    }
}
