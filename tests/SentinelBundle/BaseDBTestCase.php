<?php

namespace NS\SentinelBundle\Tests;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of BaseDBTestCase
 *
 * @author gnat
 * @author Benjamin Grandfond
 * @since  2011-07-29
 */
abstract class BaseDBTestCase extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function setUp()
    {
        // Boot the AppKernel in the test environment and with the debug.
        self::bootKernel();

        // Store the container and the entity manager in test case properties
        $this->entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    protected function generateSchema()
    {
        // Get the metadata of the application to create the schema.
        $metadata = $this->getMetadata();

        if (!empty($metadata)) {
            // Create SchemaTool
            $tool = new SchemaTool($this->entityManager);
            $tool->createSchema($metadata);
        } else {
            throw new SchemaException('No Metadata Classes to process.');
        }
    }

    /**
     * Overwrite this method to get specific metadata.
     *
     * @return array
     */
    protected function getMetadata()
    {
        return $this->entityManager->getMetadataFactory()->getAllMetadata();
    }
}
