<?php

namespace NS\SentinelBundle\Twig;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig_Environment;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Description of Templates
 *
 * @author gnat
 */
class CaseTemplates extends Twig_Extension
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     *
     * @param AuthorizationCheckerInterface $checkerInterface
     * @param Twig_Environment $twig
     */
    public function __construct(AuthorizationCheckerInterface $checkerInterface, Twig_Environment $twig)
    {
        $this->authChecker = $checkerInterface;
        $this->twig        = $twig;
    }

    /**
     *
     * @return array
     */
    public function getFunctions()
    {
        $isSafe = ['is_safe' => ['html']];

        return [
            new Twig_SimpleFunction('case_index_template', [$this, 'renderTable'], $isSafe),
        ];
    }

    /**
     *
     * @param array $results
     * @param string $tableId
     * @return string
     */
    public function renderTable($results, $tableId)
    {
        $params = ['results' => $results, 'tableId' => $tableId];

        if ($this->authChecker->isGranted('ROLE_SITE_LEVEL')) {
            return $this->twig->render('NSSentinelBundle:Case:site.html.twig', $params);
        } elseif ($this->authChecker->isGranted('ROLE_COUNTRY_LEVEL')) {
            return $this->twig->render('NSSentinelBundle:Case:country.html.twig', $params);
        } elseif ($this->authChecker->isGranted('ROLE_REGION_LEVEL')) {
            return $this->twig->render('NSSentinelBundle:Case:region.html.twig', $params);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'twig_case_templates';
    }
}
