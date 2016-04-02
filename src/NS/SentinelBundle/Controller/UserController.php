<?php
namespace NS\SentinelBundle\Controller;

use NS\SentinelBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Method(methods={"GET","POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function profileAction(Request $request)
    {
        $entityMgr = $this->get('doctrine.orm.entity_manager');
        $user = $entityMgr->getRepository('NSSentinelBundle:User')->find($this->getUser()->getId());

        $form = $this->createForm(new UserType(), $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $factory = $this->get('security.encoder_factory');
            $user    = $form->getData();
            $encoder = $factory->getEncoder($user);

            if ($user->getPlainPassword()) {
                $user->setPassword($encoder->encodePassword($user->getPlainPassword(), $user->getSalt()));
            }

            $entityMgr->persist($user);
            $entityMgr->flush();

            $this->get('ns_flash')->addSuccess(null, null, "User Successfully updated");

            return $this->redirect($this->generateUrl('userProfile'));
        }

        return $this->render('NSSentinelBundle:User:profile.html.twig', array('form' => $form->createView(), 'user'=>$this->getUser()));
    }
}
