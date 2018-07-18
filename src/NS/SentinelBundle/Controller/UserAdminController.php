<?php

namespace NS\SentinelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use NS\SentinelBundle\Form\Types\Role;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;

class UserAdminController extends Controller
{
    /**
     * @Route("/ajax/acls", name="adminACLAjaxAutocomplete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function ajaxAccountContactAction(Request $request)
    {
        $vars = $request->request->all();
        $secondaryTypeValue = $vars['secondary-field'];
        if (empty($secondaryTypeValue)) {
            return new Response("Please select the type first", 400, ['Autocomplete-Error' => 'The Access Level is required']);
        }

        $role = new Role($secondaryTypeValue);

        return $this->get('ns.ajax_autocompleter')->getAutocomplete($role->getClassMatch(), 'name');
    }
}
