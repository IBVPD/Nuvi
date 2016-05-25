<?php

namespace NS\ImportBundle\Tests\Form;

use \NS\ImportBundle\Form\ClassType;
use \Symfony\Component\Form\Test\TypeTestCase;

/**
 * Description of AllFormsTest
 *
 * @author gnat
 */
class AllFormsTest extends TypeTestCase
{

    public function testClassType()
    {
//        $choices = array(
//            'FullyQualifiedClassName'  => 'Friendly Class Name',
//            'FullyQualifiedClassName1' => 'Friendly Class Name 1',
//            'FullyQualifiedClassName2' => 'Friendly Class Name 2',
//        );

        $formData = array('FullyQualifiedClassName1');
        $form = $this->factory->create(ClassType::class);
        $form->submit($formData);

        $this->assertTrue($form->isValid());
//        $this->assertTrue($form->isSynchronized());
//        $this->assertEquals($formData, $form->getData());
//        $view     = $form->createView();
//        $children = $view->children;
//
//        foreach (array_keys($formData) as $key)
//        {
//            $this->assertArrayHasKey($key, $children);
//        }
    }
}
