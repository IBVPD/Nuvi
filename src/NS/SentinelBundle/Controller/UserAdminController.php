<?php

namespace NS\SentinelBundle\Controller;

use Exception;
use NS\SentinelBundle\Form\Types\Role;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAdminController extends Controller
{
    /**
     * @Route("/ajax/acls", name="adminACLAjaxAutocomplete")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function ajaxAccountContactAction(Request $request): Response
    {
        $vars = $request->request->all();
        $secondaryTypeValue = $vars['secondary-field'];
        if (empty($secondaryTypeValue)) {
            return new Response('Please select the type first', Response::HTTP_BAD_REQUEST, ['Autocomplete-Error' => 'The Access Level is required']);
        }

        $role = new Role($secondaryTypeValue);

        return $this->get('ns.ajax_autocompleter')->getAutocomplete($role->getClassMatch(), 'name');
    }
}
