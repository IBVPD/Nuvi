<?php

namespace NS\ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use NS\ImportBundle\Converter\Expression\ConditionConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ConditionController extends Controller
{
    /**
     * @param Request $request
     * @Route("/ajax/conditions-as-expression",name="adminConditionAjaxConverter")
     * @return JsonResponse
     */
    public function conditionExpressionAction(Request $request)
    {
        $preConditions = json_decode($request->request->get('conditions'), true);
        $string = null;
        if (is_array($preConditions)) {
            $converter = new ConditionConverter();
            $string = $converter->toArray($preConditions);
        }

        return new JsonResponse($string);
    }
}
