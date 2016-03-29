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
     * @param TranslatorInterface $translator
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
        return array(new \Twig_SimpleFunction('import_actions',array($this,'importActions'),array('is_safe'=>array('html','js'))));
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
            $output[] = sprintf('<a class="btn btn-xs btn-success" href="%s">%s <i class="fa fa-recycle"></i></button>',$this->router->generate('importResubmit',array('id'=>$import->getId())),'Re-submit');
        } elseif(!$import->isQueued() && !$import->hasError()) {
            $output[] = sprintf('<a class="btn btn-xs btn-success" href="%s">%s <i class="fa fa-clock-o"></i></button>',$this->router->generate('importResubmit',array('id'=>$import->getId())),'Queue');
        }

        if($import->hasError()) {
            if(!$import->isComplete()) {
                $output[] = sprintf('<a class="btn btn-xs btn-success" href="%s">%s <i class="fa fa-repeat"></i></button>',$this->router->generate('importResubmit',array('id'=>$import->getId())),'Restart');
            }
            $output[] = sprintf('<a class="btn btn-xs btn-info" href="#" onclick="$(\'#progress-%d-exceptions\').toggle();">Toggle Errors <i class="fa fa-exclamation-triangle"></i></a>',$import->getId());
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
