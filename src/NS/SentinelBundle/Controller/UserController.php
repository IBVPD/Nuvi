<?php
namespace NS\SentinelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of UserController
 *
 * @author gnat
 * @Route("/{_locale}")
 */
class UserController extends Controller
{
    /**
     * @Route("/profile",name="userProfile")
     * @Template()
     */
    public function profileAction(Request $request)
    {
        $em   = $this->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('NSSentinelBundle:User')->find($this->getUser()->getId());

        $form = $this->createForm(new \NS\SentinelBundle\Form\UserType(),$user);
        $form->handleRequest($request);

        if($form->isValid())
        {
            $factory = $this->get('security.encoder_factory');
            $user    = $form->getData();
            $encoder = $factory->getEncoder($user);

            if($user->getPlainPassword())
                $user->setPassword( $encoder->encodePassword($user->getPlainPassword(),$user->getSalt()) );

            $em->persist($user);
            $em->flush();

            $this->get('ns_flash')->addSuccess(null, null, "User Successfully updated");

            return $this->redirect($this->generateUrl('userProfile'));
        }

        return array('form' => $form->createView(),'user'=>$this->getUser());
    }

    /**
     * @Template()
     */
    public function regionDashboardAction()
    {
        return array();
    }

    /**
     * @Template()
     */
    public function countryDashboardAction()
    {
        return array();
    }

    /**
     * @Template()
     */
    public function siteDashboardAction()
    {
        return array();
    }

    /**
     * @Template()
     */
    public function labDashboardAction()
    {
        return array();
    }
}
