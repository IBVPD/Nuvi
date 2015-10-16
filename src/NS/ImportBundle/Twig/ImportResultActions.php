<?php

namespace NS\ImportBundle\Twig;

use NS\ImportBundle\Entity\Import;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

class ImportResultActions extends \Twig_Extension
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * ImportResultActions constructor.
     * @param RouterInterface $router
     * @param Translator $translator
     */
    public function __construct(RouterInterface $router, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function getFunctions()
    {
        return array(new \Twig_SimpleFunction('import_actions',array($this,'importActions'),array('is_safe'=>array('html'))));
    }

    /**
     * @param Import $import
     *
     * @return string
     */
    public function importActions(Import $import)
    {
        $output = array();

        if($import->isComplete()) {
            $output[] = sprintf('<a class="btn btn-xs btn-success" href="%s">%s</button>',$this->router->generate('importResubmit',array('id'=>$import->getId())),'Re-submit');
        }

        if(!$import->isComplete() && !$import->isQueued()) {
            $output[] = sprintf('<a class="btn btn-xs btn-success" href="%s">%s</button>',$this->router->generate('importResubmit',array('id'=>$import->getId())),'Queue');
        }

        return implode('&nbsp',$output);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'ImportResultAction';
    }
}
