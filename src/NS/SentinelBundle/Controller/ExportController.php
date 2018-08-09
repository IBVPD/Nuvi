<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 03/03/17
 * Time: 11:20 AM
 */

namespace NS\SentinelBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ExportController
 * @package NS\SentinelBundle\Controller
 *
 * @Route("/{_locale}/fields")
 */
class ExportController extends Controller
{
    /**
     * @Route("/{type}",name="exportFields")
     *
     * @param Request $request
     * @param $type
     * @return Response
     */
    public function fieldsAction(Request $request, $type)
    {
        $obj = $this->get('ns_sentinel.object_initializer')->initializeObject($type);
        if ($request->isXmlHttpRequest() || $request->query->has('json')) {
            return new JsonResponse($obj, 200, [], true);
        } else {
            $out = [];
            $obj = json_decode($obj);

            if (isset($obj->ibd)) {
                $out['IBD'] = get_object_vars($obj->ibd);
            }

            if (isset($obj->meningitis)) {
                $out['Meningitis'] = get_object_vars($obj->meningitis);
            }

            if (isset($obj->pneumonia)) {
                $out['Pneumonia'] = get_object_vars($obj->pneumonia);
            }

            if (isset($obj->rotavirus)) {
                $out['Rotavirus'] = get_object_vars($obj->rotavirus);
            }

            if (isset($obj->siteLab)) {
                $out['Site Lab'] = get_object_vars($obj->siteLab);
            }

            if (isset($obj->rl)) {
                $out['Regional Lab'] = get_object_vars($obj->rl);
            }

            if (isset($obj->nl)) {
                $out['National Lab'] = get_object_vars($obj->nl);
            }

            foreach ($out as &$fields) {
                foreach ($fields as $key => &$field) {
                    if ($field instanceof \stdClass) {
                        $field = get_object_vars($field);

                        if (isset($field['options']) && $field['options'] instanceof \stdClass) {
                            $options = get_object_vars($field['options']);
                            $field['options'] = $options;
                        }
                    }
                }
            }

            return $this->render('NSSentinelBundle:Export:fields.html.twig', ['obj' => $out]);
        }
    }
}
