<?php

namespace NS\SentinelBundle\Twig;

use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\Meningitis\Meningitis;
use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
use NS\SentinelBundle\Entity\RotaVirus;
use RuntimeException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CaseActions extends AbstractExtension implements TranslationContainerInterface
{
    private const
        VIEW = 'View',
        EDIT_EPI = 'Edit Epi Data',
        EDIT_LAB = 'Edit Site Lab Data',
        EDIT_NL = 'Edit National Lab Data',
        EDIT_RRL = 'Edit Regional Reference Lab Data',
        EDIT_OUT = 'Edit Outcome Data',
        EPI = 'EPI',
        LAB = 'Lab',
        NL = 'NL',
        RRL = 'RRL',
        OUT = 'Outcome';

    /** @var AuthorizationCheckerInterface */
    private $authChecker;

    /** @var TranslatorInterface */
    private $translator;

    /** @var RouterInterface */
    private $router;

    public function __construct(AuthorizationCheckerInterface $checkerInterface, TranslatorInterface $trans, RouterInterface $router)
    {
        $this->authChecker = $checkerInterface;
        $this->translator  = $trans;
        $this->router      = $router;
    }

    public function getFunctions(): array
    {
        $isSafe = ['is_safe' => ['html']];
        return [
            new TwigFunction('case_big_actions', [$this, 'getBigActions'], $isSafe),
            new TwigFunction('case_sm_actions', [$this, 'getSmallActions'], $isSafe),
        ];
    }

    public function getBaseRoute(BaseCase $object): string
    {
        if ($object instanceof IBD) {
            return 'ibd';
        }

        if ($object instanceof RotaVirus) {
            return 'rotavirus';
        }

        if ($object instanceof Meningitis) {
            return 'meningitis';
        }

        if ($object instanceof Pneumonia) {
            return 'pneumonia';
        }

        throw new RuntimeException('Unable to determine base route got object: ' . get_class($object));
    }

    /**
     * @param BaseCase $row
     * @param bool     $includeIndex
     *
     * @return string
     */
    public function getBigActions(BaseCase $row, $includeIndex = true): string
    {
        $baseRoute = $this->getBaseRoute($row);

        $out = '';
        if ($includeIndex) {
            $out = sprintf('<a href="%s" class="btn btn-xs btn-info"><i class="fa fa-list bigger-120"></i></a>', $this->router->generate($baseRoute . 'Index'));
        }

        $out .= sprintf('<a href="%s" class="btn btn-xs btn-info" data-rel="tooltip" title="%s"><i class="fa fa-eye bigger-120"></i></a>',
            $this->generate("{$baseRoute}Show", $row),
            $this->translator->trans(/** @Ignore */ self::VIEW));

        if ($this->authChecker->isGranted('ROLE_CAN_CREATE')) {
            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_CASE')) {
                $out .= sprintf('<a href="%s" class="btn btn-xs btn-info" data-rel="tooltip" title="%s"><i class="fa fa-edit bigger-120"></i> %s</a>',
                    $this->generate($baseRoute . 'Edit', $row),
                    $this->translator->trans(/** @Ignore */
                        self::EDIT_EPI),
                    $this->translator->trans(/** @Ignore */
                        self::EPI));
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_LAB')) {
                $out .= sprintf('<a href="%s" class="btn btn-xs btn-info" data-rel="tooltip" title="%s"><i class="%s bigger-120"></i> %s</a>',
                    $this->generate($baseRoute . 'LabEdit', $row),
                    $this->translator->trans(/** @Ignore */
                        self::EDIT_LAB),
                    ($row->hasSiteLab() ? 'fa fa-edit' : 'fa fa-plus'),
                    $this->translator->trans(/** @Ignore */
                        self::LAB));
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_NL_LAB') && ($row->getSentToNationalLab() || $row->hasNationalLab())) {
                $out .= sprintf('<a href="%s" class="btn btn-xs btn-info" data-rel="tooltip" title="%s"><i class="%s bigger-120"></i> %s</a>',
                    $this->generate($baseRoute . 'NLEdit', $row),
                    $this->translator->trans(/** @Ignore */
                        self::EDIT_NL),
                    ($row->hasNationalLab() ? 'fa fa-edit' : 'fa fa-plus'),
                    $this->translator->trans(/** @Ignore */
                        self::NL));
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_RRL_LAB') && ($row->getSentToReferenceLab() || $row->hasReferenceLab())) {
                $out .= sprintf('<a href="%s" class="btn btn-xs btn-info" data-rel="tooltip" title="%s"><i class="%s bigger-120"></i> %s</a>',
                    $this->generate($baseRoute . 'RRLEdit', $row),
                    $this->translator->trans(/** @Ignore */
                        self::EDIT_RRL),
                    ($row->hasReferenceLab() ? 'fa fa-edit' : 'fa fa-plus'),
                    $this->translator->trans(/** @Ignore */
                        self::RRL));
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_CASE')) {
                $out .= sprintf('<a href="%s" class="btn btn-xs btn-info" data-rel="tooltip" title="%s"><i class="fa fa-edit bigger-120"></i> %s</a>',
                    $this->generate($baseRoute . 'OutcomeEdit', $row),
                    $this->translator->trans(/** @Ignore */
                        self::EDIT_OUT),
                    $this->translator->trans(/** @Ignore */
                        self::OUT));
            }
        }

        return $out;
    }

    /**
     * @param $row
     *
     * @return string
     */
    public function getSmallActions($row): string
    {
        $baseRoute = $this->getBaseRoute($row);

        $view = $this->translator->trans(/** @Ignore */
            self::VIEW);

        $out = sprintf('<button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog fa fa-only bigger-110"></i></button>
            <ul class="dropdown-menu dropdown-yellow pull-right dropdown-caret dropdown-close dropdown-menu-right">
            <li><a href="%s" title="%s"><span class="green"><i class="fa fa-eye bigger-120"></i> %s</span></a></li>', $this->generate($baseRoute . 'Show', $row), $view, $view);

        if ($this->authChecker->isGranted('ROLE_CAN_CREATE')) {
            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_CASE')) {
                $out .= sprintf('<li><a href="%s" title="%s"><span class="green"><i class="fa fa-edit bigger-120"></i> %s</span></a></li>',
                    $this->generate($baseRoute . 'Edit', $row),
                    $this->translator->trans(/** @Ignore */
                        self::EDIT_EPI),
                    $this->translator->trans(/** @Ignore */
                        self::EPI));
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_LAB')) {
                $out .= sprintf('<li><a href="%s" title="%s"><span class="green"><i class="%s bigger-120"></i> %s</span></a></li>',
                    $this->generate($baseRoute . 'LabEdit', $row),
                    $this->translator->trans(/** @Ignore */
                        self::EDIT_LAB),
                    ($row->hasSiteLab() ? 'fa fa-edit' : 'fa fa-plus'),
                    $this->translator->trans(/** @Ignore */
                        self::LAB));
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_NL_LAB') && ($row->getSentToNationalLab() || $row->hasNationalLab())) {
                $out .= sprintf('<li><a href="%s" title="%s"><span class="green"><i class="%s bigger-120"></i> %s</span></a></li>',
                    $this->generate($baseRoute . 'NLEdit', $row),
                    $this->translator->trans(/** @Ignore */
                        self::EDIT_NL),
                    ($row->hasNationalLab() ? 'fa fa-edit' : 'fa fa-plus'),
                    $this->translator->trans(/** @Ignore */
                        self::NL));
            }

            if ($this->authChecker->isGranted('ROLE_CAN_CREATE_RRL_LAB') && ($row->getSentToReferenceLab() || $row->hasReferenceLab())) {
                $out .= sprintf('<li><a href="%s" title="%s"><span class="green"><i class="%s bigger-120"></i> %s</span></a></li>',
                    $this->generate($baseRoute . 'RRLEdit', $row),
                    $this->translator->trans(/** @Ignore */
                        self::EDIT_RRL),
                    ($row->hasReferenceLab() ? 'fa fa-edit' : 'fa fa-plus'),
                    $this->translator->trans(/** @Ignore */
                        self::RRL));
            }
        }

        $out .= '</ul>';

        return $out;
    }

    /**
     * @param $route
     * @param $row
     *
     * @return string
     */
    private function generate($route, $row): ?string
    {
        return $this->router->generate($route, ['id' => $row->getId()]);
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public static function getTranslationMessages(): array
    {
        return [
            new Message(self::VIEW),
            new Message(self::EPI),
            new Message(self::LAB),
            new Message(self::NL),
            new Message(self::RRL),
            new Message(self::OUT),
            new Message(self::EDIT_EPI),
            new Message(self::EDIT_LAB),
            new Message(self::EDIT_NL),
            new Message(self::EDIT_RRL),
            new Message(self::EDIT_OUT),
        ];
    }
}
