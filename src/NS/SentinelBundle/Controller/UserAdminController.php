<?php

namespace NS\SentinelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use NS\SentinelBundle\Form\Types\Role;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UserAdminController extends Controller
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
