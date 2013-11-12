<?php

namespace NS\SentinelBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use NS\SentinelBundle\Form\Type\Role;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserAdminController extends CRUDController
{
    /**
     * @Route("/ajax/acls", name="adminACLAjaxAutocomplete")
     */
    public function ajaxAccountContactAction(Request $request)
    {
        $vars = $request->request->all();
        $secondaryTypeValue = $vars['secondary-field'];
        $role = new Role($secondaryTypeValue);

        return $this->get('ns.ajax_autocompleter')->getAutocomplete($role->getClassMatch(),'name');
    }

}
