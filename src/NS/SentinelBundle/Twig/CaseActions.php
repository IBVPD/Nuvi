<?php

namespace NS\SentinelBundle\Twig;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Translation\TranslatorInterface;
use \Symfony\Component\Routing\RouterInterface;

/**
 * Description of CaseActions
 *
 * @author gnat
 */
class CaseActions extends \Twig_Extension
{
    private $securityContext;
    private $translator;
    private $router;

    function __construct(SecurityContextInterface $securityContext, TranslatorInterface $trans, RouterInterface $router)
    {
        $this->securityContext = $securityContext;
        $this->translator      = $trans;
        $this->router          = $router;
    }

    public function getFunctions()
    {
        $isSafe = array('is_safe' => array('html'));
        return array(
            'case_big_actions' => new \Twig_Function_Method($this, 'getBigActions', $isSafe),
            'case_sm_actions'  => new \Twig_Function_Method($this, 'getSmallActions', $isSafe),
        );
    }

    public function getBaseRoute($object)
    {
        return ($object instanceOf \NS\SentinelBundle\Entity\IBD) ? 'ibd' : 'rotavirus';
    }

    public function getBigActions($row, $includeIndex = false)
    {
        $baseRoute = $this->getBaseRoute($row);

        $out = '';
        if ($includeIndex)
            $out .= '<a href="' . $this->router->generate($baseRoute . 'Index') . '" class="btn btn-xs btn-info"><i class="icon-list bigger-120"></i></a>';

        $out = '<a href="' . $this->router->generate($baseRoute . 'Show', array(
                'id' => $row->getId())) . '" class="btn btn-xs btn-info"><i class="icon-eye-open bigger-120"></i></a>';

        if ($this->securityContext->isGranted('ROLE_CAN_CREATE'))
        {
            if ($this->securityContext->isGranted('ROLE_CAN_CREATE_CASE'))
                $out .= '<a href="' . $this->router->generate($baseRoute . 'Edit', array(
                        'id' => $row->getId())) . '" class="btn btn-xs btn-info"><i class="icon-edit bigger-120"></i> ' . $this->translator->trans('EPI') . '</a>';

            if ($this->securityContext->isGranted('ROLE_CAN_CREATE_LAB'))
                $out .= '<a href="' . $this->router->generate($baseRoute . 'LabEdit', array(
                        'id' => $row->getId())) . '" class="btn btn-xs btn-info"><i class="' . ($row->hasSiteLab() ? 'icon-edit' : 'icon-plus') . ' bigger-120"></i> ' . $this->translator->trans('Lab') . '</a>';

            if ($this->securityContext->isGranted('ROLE_CAN_CREATE_NL_LAB') && $row->getSentToNationalLab())
                $out .= '<a href="' . $this->router->generate($baseRoute . 'NLEdit', array(
                        'id' => $row->getId())) . '" class="btn btn-xs btn-info"><i class="' . ($row->hasNationalLab() ? 'icon-edit' : 'icon-plus') . ' bigger-120"></i>' . $this->translator->trans('NL') . '</a>';

            if ($this->securityContext->isGranted('ROLE_CAN_CREATE_RRL_LAB') && $row->getSentToReferenceLab())
                $out .= '<a href="' . $this->router->generate($baseRoute . 'RRLEdit', array(
                        'id' => $row->getId())) . '" class="btn btn-xs btn-info"><i class="' . ($row->hasReferenceLab() ? 'icon-edit' : 'icon-plus') . ' bigger-120"></i>' . $this->translator->trans('RRL') . '</a>';

            if ($this->securityContext->isGranted('ROLE_CAN_CREATE_CASE'))
                $out .= '<a href="' . $this->router->generate($baseRoute . 'OutcomeEdit', array(
                        'id' => $row->getId())) . '" class="btn btn-xs btn-info"><i class="icon-edit bigger-120"></i> ' . $this->translator->trans('Outcome') . '</a>';
        }

        return $out;
    }

    public function getSmallActions($row)
    {
        $baseRoute = $this->getBaseRoute($row);

        $out = '<button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown"><i class="icon-cog icon-only bigger-110"></i></button>
            <ul class="dropdown-menu dropdown-only-icon dropdown-yellow pull-right dropdown-caret dropdown-close">
            <li><a href="' . $this->router->generate($baseRoute . 'Show', array(
                'id' => $row->getId())) . '" class="tooltip-info" data-rel="tooltip" title="View"><span class="blue"><i class="icon-eye-open bigger-120"></i></span></a></li>';

        if ($this->securityContext->isGranted('ROLE_CAN_CREATE'))
        {
            if ($this->securityContext->isGranted('ROLE_CAN_CREATE_CASE'))
                $out .= '<li><a href="' . $this->router->generate($baseRoute . 'Edit', array(
                        'id' => $row->getId())) . '" class="tooltip-success" data-rel="tooltip" title="Edit"><span class="green"><i class="icon-edit bigger-120"></i> ' . $this->translator->trans('EPI') . '</span></a></li>';

            if ($this->securityContext->isGranted('ROLE_CAN_CREATE_LAB'))
                $out .= '<li><a href="' . $this->router->generate($baseRoute . 'LabEdit', array(
                        'id' => $row->getId())) . '" class="tooltip-success"><span class="green"><i class="' . ($row->hasSiteLab() ? 'icon-edit' : 'icon-plus') . ' bigger-120"></i> ' . $this->translator->trans('Lab') . '</span></a></li>';

            if ($this->securityContext->isGranted('ROLE_CAN_CREATE_NL_LAB') && $row->getSentToNationalLab())
                $out .= '<li><a href="' . $this->router->generate($baseRoute . 'NLEdit', array(
                        'id' => $row->getId())) . '" class="tooltip-success"><span class="green"><i class="' . ($row->hasNationalLab() ? 'icon-edit' : 'icon-plus') . ' bigger-120"></i> ' . $this->translator->trans('NL') . '</span></a></li>';

            if ($this->securityContext->isGranted('ROLE_CAN_CREATE_RRL_LAB') && $row->getSentToReferenceLab())
                $out .= '<li><a href="' . $this->router->generate($baseRoute . 'RRLEdit', array(
                        'id' => $row->getId())) . '" class="tooltip-success"><span class="green"><i class="' . ($row->hasReferenceLab() ? 'icon-edit' : 'icon-plus') . ' bigger-120"></i> ' . $this->translator->trans('RRL') . '</span></a></li>';
        }

        $out .= '</ul>';

        return $out;
    }

    public function getName()
    {
        return 'twig_case_actions';
    }

}
