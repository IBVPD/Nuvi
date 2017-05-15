<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 16/03/17
 * Time: 10:45 AM
 */

namespace NS\ImportBundle\Formatter;

use Exporter\Formatter\DataFormatterInterface;
use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\Translation\TranslatorInterface;

class ArrayChoiceFormatter implements DataFormatterInterface
{
    /** @var TranslatorInterface */
    private $translator;

    /** @var bool */
    private $pahoFormat = false;

    /**
     * ArrayChoiceFormatter constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function supports($data)
    {
        return $data instanceof ArrayChoice;
    }

    /**
     * @inheritDoc
     * @param ArrayChoice $data
     */
    public function format($data, PropertyPath $propertyPath)
    {
        if ($data->equal(ArrayChoice::NO_SELECTION)) {
            return null;
        }

        return ($this->pahoFormat) ? sprintf('%d => %s',$data->getValue(), /** @Ignore */$this->translator->trans($data->__toString())): $data->getValue();
    }

    /**
     * @inheritDoc
     */
    public function getPriority()
    {
        return 5;
    }

    public function usePahoFormat()
    {
        $this->pahoFormat = true;
    }

    public function useRegularFormat()
    {
        $this->pahoFormat = false;
    }
}
