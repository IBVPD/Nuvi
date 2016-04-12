<?php

namespace NS\SentinelBundle\Twig;

use NS\SentinelBundle\Entity\BaseCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\RouterInterface;
use NS\SentinelBundle\Entity\IBD;

/**
 * Description of CaseActions
 *
 * @author gnat
 */
class CaseActions extends \Twig_Extension
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param AuthorizationCheckerInterface $checkerInterface
     * @param TranslatorInterface $trans
     * @param RouterInterface $router
     */
    public function __construct(AuthorizationCheckerInterface $checkerInterface, TranslatorInterface $trans, RouterInterface $router)
    {
        $this->authChecker = $checkerInterface;
        $this->translator = $trans;
        $this->router = $router;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        $isSafe = array('is_safe' => array('html'));
        return array(
            new \Twig_SimpleFunction('case_big_actions', array($this, 'getBigActions'), $isSafe),
            new \Twig_SimpleFunction('case_sm_actions', array($this, 'getSmallActions'), $isSafe),
        );
    }

    /**
     * @param BaseCase $object
     * @return string
     */
    public function getBaseRoute(BaseCase $object)
    {
        return ($object instanceof IBD) ? 'ibd' : 'rotavirus';
    }

    /**
     * @param BaseCase $row
     * @param bool $includeIndex
     * @return string
     */
    public function getBigActions(BaseCase $row, $includeIndex = true)
    {
        $baseRoute = $this->getBaseRoute($row);

        $out = '';

        if ($includeIndex) {
            $out = sprintf('<a href="%s" class="btn btn-xs btn-info"><i class="fa fa-list bigger-120"></i></a>', $this->router->generate($baseRoute . 'Index'));
        }

        $out .= sprintf('<a href="%s" class="btn btn-xs btn-info"><i class="fa fa-eye bigger-120"></i></a>', $this->router->generate($baseRoute . 'Show', array('id' => $row->getId()), UrlGeneratorInterface::ABSOLUTE_PATH));

        if ($this->authChecker->isGranted('ROLE_CAN_CREATE')) {
            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_CASE')) {
                $out .= sprintf('<a href="%s" class="btn btn-xs btn-info"><i class="fa fa-edit bigger-120"></i> %s</a>', $this->router->generate($baseRoute . 'Edit', array('id' => $row->getId()), UrlGeneratorInterface::ABSOLUTE_PATH), $this->translator->trans('EPI'));
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_LAB')) {
                $out .= sprintf('<a href="%s" class="btn btn-xs btn-info"><i class="%s bigger-120"></i> %s</a>', $this->router->generate($baseRoute . 'LabEdit', array('id' => $row->getId()), UrlGeneratorInterface::ABSOLUTE_PATH), ($row->hasSiteLab() ? 'fa fa-edit' : 'fa fa-plus'), $this->translator->trans('Lab'));
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_NL_LAB') && ($row->getSentToNationalLab() || $row->hasNationalLab())) {
                $out .= sprintf('<a href="%s" class="btn btn-xs btn-info"><i class="%s bigger-120"></i> %s</a>', $this->router->generate($baseRoute . 'NLEdit', array('id' => $row->getId()), UrlGeneratorInterface::ABSOLUTE_PATH), ($row->hasNationalLab() ? 'fa fa-edit' : 'fa fa-plus'), $this->translator->trans('NL'));
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_RRL_LAB') && ($row->getSentToReferenceLab() || $row->hasReferenceLab())) {
                $out .= sprintf('<a href="%s" class="btn btn-xs btn-info"><i class="%s bigger-120"></i> %s</a>', $this->router->generate($baseRoute . 'RRLEdit', array('id' => $row->getId()), UrlGeneratorInterface::ABSOLUTE_PATH), ($row->hasReferenceLab() ? 'fa fa-edit' : 'fa fa-plus'), $this->translator->trans('RRL'));
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_CASE')) {
                $out .= sprintf('<a href="%s" class="btn btn-xs btn-info"><i class="fa fa-edit bigger-120"></i> %s</a>', $this->router->generate($baseRoute . 'OutcomeEdit', array('id' => $row->getId()), UrlGeneratorInterface::ABSOLUTE_PATH), $this->translator->trans('Outcome'));
            }
        }

        return $out;
    }

    /**
     * @param $row
     * @return string
     */
    public function getSmallActions($row)
    {
        $baseRoute = $this->getBaseRoute($row);

        $out = sprintf('<button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog fa fa-only bigger-110"></i></button>
            <ul class="dropdown-menu dropdown-yellow pull-right dropdown-caret dropdown-close dropdown-menu-right">
            <li><a href="%s" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="fa fa-eye bigger-120"></i></span></a></li>', $this->router->generate($baseRoute . 'Show', array('id' => $row->getId())));

        if ($this->authChecker->isGranted('ROLE_CAN_CREATE')) {
            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_CASE')) {
                $out .= sprintf('<li><a href="%s" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="fa fa-edit bigger-120"></i> %s</span></a></li>', $this->router->generate($baseRoute . 'Edit', array('id' => $row->getId())), $this->translator->trans('EPI'));
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_LAB')) {
                $out .= sprintf('<li><a href="%s" class="tooltip-success"><span class="green"><i class="%s bigger-120"></i> %s</span></a></li>', $this->router->generate($baseRoute . 'LabEdit', array('id' => $row->getId())), ($row->hasSiteLab() ? 'fa fa-edit' : 'fa fa-plus'), $this->translator->trans('Lab'));
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_NL_LAB') && ($row->getSentToNationalLab() || $row->hasNationalLab())) {
                $out .= sprintf('<li><a href="%s" class="tooltip-success"><span class="green"><i class="%s bigger-120"></i> %s</span></a></li>', $this->router->generate($baseRoute . 'NLEdit', array('id' => $row->getId())), ($row->hasNationalLab() ? 'fa fa-edit' : 'fa fa-plus'), $this->translator->trans('NL'));
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_RRL_LAB') && ($row->getSentToReferenceLab() || $row->hasReferenceLab())) {
                $out .= sprintf('<li><a href="%s" class="tooltip-success"><span class="green"><i class="%s bigger-120"></i> %s</span></a></li>', $this->router->generate($baseRoute . 'RRLEdit', array('id' => $row->getId())), ($row->hasReferenceLab() ? 'fa fa-edit' : 'fa fa-plus'), $this->translator->trans('RRL'));
            }
        }

        $out .= '</ul>';

        return $out;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'twig_case_actions';
    }
}
