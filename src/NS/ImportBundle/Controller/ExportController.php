<?php

namespace NS\ImportBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of ExportController
 *
 * @author gnat
 * @Route("/{_locale}/export")
 */
class ExportController extends BaseController
{
    /**
     * @Route("/",name="exportIndex")
     * @Method(methods={"GET","POST"})
     */
    public function indexAction()
    {
        $forms = $this->getForms();

        return $this->render('NSImportBundle:Export:index.html.twig', [
            'forms' => $forms,
        ]);
    }

}
