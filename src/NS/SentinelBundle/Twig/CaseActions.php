<?php

namespace NS\SentinelBundle\Twig;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use \Symfony\Component\Routing\RouterInterface;
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
            new \Twig_SimpleFunction('case_big_actions',array($this, 'getBigActions'),$isSafe),
            new \Twig_SimpleFunction('case_sm_actions', array($this, 'getSmallActions'),$isSafe),
        );
    }

    /**
     * @param $object
     * @return string
     */
    public function getBaseRoute($object)
    {
        return ($object instanceOf IBD) ? 'ibd' : 'rotavirus';
    }

    /**
     * @param $row
     * @param bool|false $includeIndex
     * @return string
     */
    public function getBigActions($row, $includeIndex = false)
    {
        $baseRoute = $this->getBaseRoute($row);

        $out = '';
        if ($includeIndex) {
            $out .= '<a href="' . $this->router->generate($baseRoute . 'Index') . '" class="btn btn-xs btn-info"><i class="fa fa-list bigger-120"></i></a>';
        }

        $out = '<a href="' . $this->router->generate($baseRoute . 'Show', array(
                'id' => $row->getId())) . '" class="btn btn-xs btn-info"><i class="fa fa-eye bigger-120"></i></a>';

        if ($this->authChecker->isGranted('ROLE_CAN_CREATE')) {
            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_CASE')) {
                $out .= '<a href="' . $this->router->generate($baseRoute . 'Edit', array(
                        'id' => $row->getId())) . '" class="btn btn-xs btn-info"><i class="fa fa-edit bigger-120"></i> ' . $this->translator->trans('EPI') . '</a>';
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_LAB')) {
                $out .= '<a href="' . $this->router->generate($baseRoute . 'LabEdit', array(
                        'id' => $row->getId())) . '" class="btn btn-xs btn-info"><i class="' . ($row->hasSiteLab() ? 'fa fa-edit' : 'fa fa-plus') . ' bigger-120"></i> ' . $this->translator->trans('Lab') . '</a>';
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_NL_LAB') && $row->getSentToNationalLab()) {
                $out .= '<a href="' . $this->router->generate($baseRoute . 'NLEdit', array(
                        'id' => $row->getId())) . '" class="btn btn-xs btn-info"><i class="' . ($row->hasNationalLab() ? 'fa fa-edit' : 'fa fa-plus') . ' bigger-120"></i>' . $this->translator->trans('NL') . '</a>';
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_RRL_LAB') && $row->getSentToReferenceLab()) {
                $out .= '<a href="' . $this->router->generate($baseRoute . 'RRLEdit', array(
                        'id' => $row->getId())) . '" class="btn btn-xs btn-info"><i class="' . ($row->hasReferenceLab() ? 'fa fa-edit' : 'fa fa-plus') . ' bigger-120"></i>' . $this->translator->trans('RRL') . '</a>';
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_CASE')) {
                $out .= '<a href="' . $this->router->generate($baseRoute . 'OutcomeEdit', array(
                        'id' => $row->getId())) . '" class="btn btn-xs btn-info"><i class="fa fa-edit bigger-120"></i> ' . $this->translator->trans('Outcome') . '</a>';
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

        $out = '<button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog fa fa-only bigger-110"></i></button>
            <ul class="dropdown-menu dropdown-only-icon dropdown-yellow pull-right dropdown-caret dropdown-close">
            <li><a href="' . $this->router->generate($baseRoute . 'Show', array(
                'id' => $row->getId())) . '" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="fa fa-eye bigger-120"></i></span></a></li>';

        if ($this->authChecker->isGranted('ROLE_CAN_CREATE')) {
            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_CASE')) {
                $out .= '<li><a href="' . $this->router->generate($baseRoute . 'Edit', array(
                        'id' => $row->getId())) . '" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="fa fa-edit bigger-120"></i> ' . $this->translator->trans('EPI') . '</span></a></li>';
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_LAB')) {
                $out .= '<li><a href="' . $this->router->generate($baseRoute . 'LabEdit', array(
                        'id' => $row->getId())) . '" class="tooltip-success"><span class="green"><i class="' . ($row->hasSiteLab() ? 'fa fa-edit' : 'fa fa-plus') . ' bigger-120"></i> ' . $this->translator->trans('Lab') . '</span></a></li>';
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_NL_LAB') && $row->getSentToNationalLab()) {
                $out .= '<li><a href="' . $this->router->generate($baseRoute . 'NLEdit', array(
                        'id' => $row->getId())) . '" class="tooltip-success"><span class="green"><i class="' . ($row->hasNationalLab() ? 'fa fa-edit' : 'fa fa-plus') . ' bigger-120"></i> ' . $this->translator->trans('NL') . '</span></a></li>';
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_RRL_LAB') && $row->getSentToReferenceLab()) {
                $out .= '<li><a href="' . $this->router->generate($baseRoute . 'RRLEdit', array(
                        'id' => $row->getId())) . '" class="tooltip-success"><span class="green"><i class="' . ($row->hasReferenceLab() ? 'fa fa-edit' : 'fa fa-plus') . ' bigger-120"></i> ' . $this->translator->trans('RRL') . '</span></a></li>';
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
