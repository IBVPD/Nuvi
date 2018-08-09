<?php

namespace NS\ApiBundle\Serializer;

use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Description of ArrayChoiceHandler
 *
 * @author gnat
 */
class ArrayChoiceHandler implements SubscribingHandlerInterface
{
    /** @var TranslatorInterface */
    private $translator;

    /**
     * ArrayChoiceHandler constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

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
        $groups = $context->attributes->get('groups');
        if (in_array('expanded', $groups->get())) {
            return $this->translatedSerialization(get_class($data), $data->getValues());
        }

        return (int)$data->getValue();
    }

    protected function translatedSerialization($className, $values)
    {
        $result = ['class' => $className, 'options' => []];

        foreach ($values as $key => $label) {
            $result['options'][$key] = $this->translator->trans(/** @Ignore */$label);
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function getSubscribingMethods()
    {
        $ret = [];

        $finder = new Finder();
        $finder
            ->in(realpath(__DIR__ . '/../../'))
            ->files()
            ->contains('extends TranslatableArrayChoice');

        foreach ($finder as $file) {
            if ($file->getPathname() !== __FILE__) {
                $lines = file($file->getPathname());
                $namespaceLines = preg_grep('/^namespace /', $lines);
                $namespaceLine = array_shift($namespaceLines);
                $match = array();
                preg_match('/^namespace (.*);$/', $namespaceLine, $match);
                $fullNamespace = array_pop($match);

                $ret[] = [
                    'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                    'format' => 'json',
                    'type' => $fullNamespace . "\\" . str_replace('.php', '', $file->getFilename()),
                    'method' => 'serializeToJson',
                ];
            }
        }

        $finder = new Finder();
        $finder
            ->in(realpath(__DIR__ . '/../../'))
            ->files()
            ->contains('extends TranslatableSetChoice');

        foreach ($finder as $file) {
            if ($file->getPathname() !== __FILE__) {
                $lines = file($file->getPathname());
                $namespaceLines = preg_grep('/^namespace /', $lines);
                $namespaceLine = array_shift($namespaceLines);
                $match = array();
                preg_match('/^namespace (.*);$/', $namespaceLine, $match);
                $fullNamespace = array_pop($match);

                $ret[] = [
                    'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                    'format' => 'json',
                    'type' => $fullNamespace . "\\" . str_replace('.php', '', $file->getFilename()),
                    'method' => 'serializeToJson',
                ];
            }
        }

        return $ret;
    }
}
