<?php

namespace NS\SentinelBundle\Twig;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CaseTemplates extends AbstractExtension
{
    /** @var AuthorizationCheckerInterface */
    private $authChecker;

    /** @var Environment */
    private $twig;

    public function __construct(AuthorizationCheckerInterface $checkerInterface, Environment $twig)
    {
        $this->authChecker = $checkerInterface;
        $this->twig        = $twig;
    }

    public function getFunctions(): array
    {
        $isSafe = ['is_safe' => ['html']];

        return [
            new TwigFunction('case_index_template', [$this, 'renderTable'], $isSafe),
        ];
    }

    /**
     *
     * @param object|array|mixed $results
     * @param string $tableId
     * @return string
     */
    public function renderTable($results, $tableId): ?string
    {
        $params = ['results' => $results, 'tableId' => $tableId];

        if ($this->authChecker->isGranted('ROLE_CAN_SEE_NAMES')) {
            return $this->twig->render('NSSentinelBundle:Case:country.html.twig', $params);
        }

        if ($this->authChecker->isGranted('ROLE_SITE_LEVEL')) {
            return $this->twig->render('NSSentinelBundle:Case:site.html.twig', $params);
        }

        if ($this->authChecker->isGranted('ROLE_COUNTRY_LEVEL')) {
            return $this->twig->render('NSSentinelBundle:Case:country.html.twig', $params);
        }

        if ($this->authChecker->isGranted('ROLE_REGION_LEVEL')) {
            return $this->twig->render('NSSentinelBundle:Case:region.html.twig', $params);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'twig_case_templates';
    }
}
