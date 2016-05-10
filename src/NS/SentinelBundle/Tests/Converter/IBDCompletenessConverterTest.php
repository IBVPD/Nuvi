<?php

namespace NS\SentinelBundle\Tests\Converter;

use NS\SentinelBundle\Converter\IBDCompletenessConverter;
use NS\SentinelBundle\Form\IBD\Types\CXRResult;

require_once dirname(__DIR__).'/../../../../app/AppKernel.php';

/**
 * Class IBDCompletenessConverterTest
 * @package NS\SentinelBundle\Tests\Converter
 */
class IBDCompletenessConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Symfony\Component\HttpKernel\AppKernel
     */
    protected $kernel;

    /**
     * @var Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    /**
     *
     */
    public function setUp()
    {
        // Boot the AppKernel in the test environment and with the debug.
        $this->kernel = new \AppKernel('test', true);
        $this->kernel->boot();

        // Store the container and the entity manager in test case properties
        $this->container     = $this->kernel->getContainer();

        parent::setUp();
    }

    /**
     *
     */
    public function tearDown()
    {
        // Shutdown the kernel.
        $this->kernel->shutdown();

        parent::tearDown();
    }

    public function testIBDFields()
    {
        $config = array(
            'case'=>array(
                array('resultField' => 'cxrResult', 'tripleChoiceField' => 'cxrDone',)
            )
        );

        $converter = new IBDCompletenessConverter($this->container->get('validator'), $config);

        $data = array(
            'cxrDone' => null,
            'cxrResult' => new CXRResult(CXRResult::CONSISTENT),
        );
        $this->assertInstanceOf('NS\SentinelBundle\Converter\IBDCompletenessConverter', $converter);

        $output = $converter->__invoke($data);
        $this->assertInstanceOf('NS\SentinelBundle\Form\Types\TripleChoice', $output['cxrDone']);
    }
}
