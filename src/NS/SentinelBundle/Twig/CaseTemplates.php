<?php

namespace NS\SentinelBundle\Twig;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Description of Templates
 *
 * @author gnat
 */
class CaseTemplates extends \Twig_Extension
{
    private $authChecker;
    private $twig;

    /**
     *
     * @param AuthorizationCheckerInterface $checkerInterface
     * @param \Twig_Environment $twig
     */
    public function __construct(AuthorizationCheckerInterface $checkerInterface, \Twig_Environment $twig)
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
        $isSafe = array('is_safe' => array('html'));

        return array(
            'case_index_template' => new \Twig_Function_Method($this, 'renderTable', $isSafe),
        );
    }

    /**
     *
     * @param array $results
     * @param string $tableId
     * @return string
     */
    public function renderTable($results, $tableId)
    {
        $params = array('results' => $results, 'tableId' => $tableId);

        if ($this->authChecker->isGranted('ROLE_SITE_LEVEL')) {
            return $this->twig->render('NSSentinelBundle:Case:site.html.twig', $params);
        } else if ($this->authChecker->isGranted('ROLE_COUNTRY_LEVEL')) {
            return $this->twig->render('NSSentinelBundle:Case:country.html.twig', $params);
        } else if ($this->authChecker->isGranted('ROLE_REGION_LEVEL')) {
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
