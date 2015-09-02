<?php

namespace NS\SentinelBundle\Exporter;

use \Exporter\Source\SourceIteratorInterface;
use \Symfony\Component\PropertyAccess\PropertyAccess;
use \Symfony\Component\PropertyAccess\PropertyPath;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * Read data from a Doctrine ArrayCollection
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 * @author gnat
 */
class DoctrineCollectionSourceIterator implements SourceIteratorInterface
{
    /**
     * @var ArrayCollection
     */
    protected $collection;

    /**
     * @var \ArrayIterator
     */
    protected $iterator;

    protected $propertyPaths;

    /**
     * @var PropertyAccess
     */
    protected $propertyAccessor;

    /**
     * @var string default DateTime format
     */
    protected $dateTimeFormat;

    /**
     * @param ArrayCollection       $collection     The Doctrine ArrayCollection
     * @param array                 $fields         Fields to export
     * @param string                $dateTimeFormat
     */
    public function __construct(ArrayCollection $collection, array $fields, $dateTimeFormat = 'r')
    {
        $this->collection = clone $collection;

        // Note : will be deprecated in Symfony 3.0, conserved for 2.2 compatibility
        // Use createPropertyAccessor() for 3.0
        // @see Symfony\Component\PropertyAccess\PropertyAccess
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();

        $this->propertyPaths = array();
        foreach ($fields as $name => $field) {
            if (is_string($name) && is_string($field)) {
                $this->propertyPaths[$name] = new PropertyPath($field);
            } else {
                $this->propertyPaths[$field] = new PropertyPath($field);
            }
        }
        $this->dateTimeFormat = $dateTimeFormat;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        $current = $this->iterator->current();

        $data = array();

        foreach ($this->propertyPaths as $name => $propertyPath)
        {
            try
            {
                $data[$name] = $this->getValue($this->propertyAccessor->getValue($current, $propertyPath));
            }
            catch (\Exception $e)
            {
                $data[$name] = $e->getMessage();//null;
            }
        }

        return $data;
    }

    /**
     * @param $value
     *
     * @return null|string
     */
    protected function getValue($value)
    {
        if (is_array($value) || $value instanceof \Traversable) {
            $value = null;
        } elseif ($value instanceof \DateTime) {
            $value = $value->format($this->dateTimeFormat);
        } elseif (is_object($value)) {
            $value = (string) $value;
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->iterator->next();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->iterator->key();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->iterator->valid();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        if ($this->iterator) {
            $this->iterator->rewind();
            return;
        }

        $this->iterator = $this->collection->getIterator();
        $this->iterator->rewind();
    }

    /**
     * @param string $dateTimeFormat
     */
    public function setDateTimeFormat($dateTimeFormat)
    {
        $this->dateTimeFormat = $dateTimeFormat;
    }

    /**
     * @return string
     */
    public function getDateTimeFormat()
    {
        return $this->dateTimeFormat;
    }
}
