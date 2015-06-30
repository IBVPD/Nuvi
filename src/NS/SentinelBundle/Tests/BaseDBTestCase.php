<?php

namespace NS\SentinelBundle\Tests;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\SchemaTool;

require_once dirname(__DIR__).'/../../../app/AppKernel.php';

/**
 * Description of BaseDBTestCase
 *
 * @author gnat
 * @author Benjamin Grandfond
 * @since  2011-07-29
 */
abstract class BaseDBTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Symfony\Component\HttpKernel\AppKernel
     */
    protected $kernel;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    public function setUp()
    {
        // Boot the AppKernel in the test environment and with the debug.
        $this->kernel = new \AppKernel('test', true);
        $this->kernel->boot();

        // Store the container and the entity manager in test case properties
        $this->container     = $this->kernel->getContainer();
        $this->entityManager = $this->container->get('doctrine.orm.entity_manager');

        // Build the schema
//        $this->generateSchema();

        parent::setUp();
    }

    public function tearDown()
    {
        // Shutdown the kernel.
        $this->kernel->shutdown();

        parent::tearDown();
    }

    protected function generateSchema()
    {
        // Get the metadata of the application to create the schema.
        $metadata = $this->getMetadata();

        if(!empty($metadata))
        {
            // Create SchemaTool
            $tool = new SchemaTool($this->entityManager);
            $tool->createSchema($metadata);
        }
        else
            throw new SchemaException('No Metadata Classes to process.');
    }

    /**
     * Overwrite this method to get specific metadata.
     *
     * @return Array
     */
    protected function getMetadata()
    {
        return $this->entityManager->getMetadataFactory()->getAllMetadata();
    }
}