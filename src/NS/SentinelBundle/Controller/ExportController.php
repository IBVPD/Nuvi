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
     * @param $type
     * @return Response
     */
    public function fieldsAction($type)
    {
        return new JsonResponse($this->get('ns_sentinel.object_initializer')->initializeObject($type), 200, [], true);
    }
}
