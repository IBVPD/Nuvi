<?php

namespace NS\SentinelBundle\Twig;

use \Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Description of Templates
 *
 * @author gnat
 */
class CaseTemplates extends \Twig_Extension
{
    private $securityContext;
    private $twig;

    public function __construct(SecurityContextInterface $context, \Twig_Environment $environment)
    {
        $this->securityContext = $context;
        $this->twig            = $environment;
    }

    public function getFunctions()
    {
        $isSafe = array('is_safe' => array('html'));

        return array(
            'case_index_template' => new \Twig_Function_Method($this, 'renderTable', $isSafe),
        );
    }

    public function renderTable($results, $tableId)
    {
        $params = array('results' => $results, 'tableId' => $tableId);

        if ($this->securityContext->isGranted('ROLE_SITE_LEVEL'))
            return $this->twig->render('NSSentinelBundle:Case:site.html.twig', $params);
        else if ($this->securityContext->isGranted('ROLE_COUNTRY'))
            return $this->twig->render('NSSentinelBundle:Case:country.html.twig', $params);
        else if ($this->securityContext->isGranted('ROLE_REGION'))
            return $this->twig->render('NSSentinelBundle:Case:region.html.twig', $params);
    }

    public function getName()
    {
        return 'twig_case_templates';
    }
}
