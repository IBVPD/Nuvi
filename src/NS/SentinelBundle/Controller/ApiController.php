<?php

namespace NS\SentinelBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\View\View;

/**
 * Description of ApiController
 *
 * @author gnat
 */
class ApiController extends \FOS\RestBundle\Controller\FOSRestController
{
    /**
    * Get single Page,
    *
    * @ApiDoc(
    *   resource = true,
    *   description = "Gets a Page for a given id",
    *   output = "NS\SentinelBundle\Entity\Meningitis",
    *   statusCodes = {
    *     200 = "Returned when successful",
    *     404 = "Returned when the page is not found"
    *   }
    * )
    *
    * @REST\View(templateVar="page")
    * @REST\Get("/api/v1/{type}/{id}.{_format}")
    *
    * @param Request $request the request object
    * @param int     $type    the object type
    * @param int     $id      the object id
    *
    * @return array
    *
    * @throws NotFoundHttpException when page not exist
    */
    public function getAction($type,$id)
    {
        try {

            switch($type)
            {
                case 'ibd':
                    $obj = $this->get('doctrine.orm.entity_manager')->getRepository('NSSentinelBundle:Meningitis')->find($id);
                    $v = new View();
                    $v->setData(array('page'=>$obj));
                    
                    return $this->handleView($v);
//                    return 
                case 'rota':
                    $v = new View();
                    $v->setData(array('page'=>array($type,$id,'here '.__LINE__)));

                    return $this->handleView($v);
//                    return $this->get('ns.model_manager')->getRepository('NSSentinelBundle:Rotavirus')->find($id);
            }
        }
        catch(\Exception $e)
        {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException("Could not find $type with id:$id");
        }
    }
}
