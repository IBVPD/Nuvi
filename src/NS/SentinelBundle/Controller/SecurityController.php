<?php

namespace NS\SentinelBundle\Controller;

use NS\SentinelBundle\Entity\User;
use NS\SentinelBundle\Filter\Type\IBD\ReportFilterType;
use NS\SentinelBundle\Form\ForgotPasswordType;
use NS\SentinelBundle\Form\ResetPasswordType;
use NS\TokenBundle\Generator\InvalidTokenException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="admin_login")
     * @Route("/login", name="login")
     * @Method(methods={"GET"})
     */
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render('NSSentinelBundle:Security:login.html.twig', [
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/forgot-password", name="forgotPassword")
     * @Method(methods={"GET","POST"})
     */
    public function forgotPasswordAction(Request $request)
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->get('email')->getData();

            /** @var User $user */
            $user = $this->get('doctrine.orm.entity_manager')->getRepository(User::class)->findOneBy(['email' => $address]);

            if ($user) {

                $fpHtml = $this->renderView('NSSentinelBundle:Mail:forgot-password.html.twig', ['user' => $user]);
                $fpText = $this->renderView('NSSentinelBundle:Mail:forgot-password.text.twig', ['user' => $user]);

                $message = new \Swift_Message('Forgotten Password');
                $message->setTo($user->getEmail(),$user->getName());
                $message->setFrom('noreply@who.int');
                $message->setBody($fpHtml,'text/html');
                $message->addPart($fpText);
                $mailer = $this->get('mailer');
                $mailer->send($message);
            }

            $this->get('ns_flash')->addSuccess('Success', 'If your email matches one on the system a message to reset your password was sent');

            return $this->redirect($this->generateUrl('admin_login'));
        }

        return $this->render('NSSentinelBundle:Security:forgotPassword.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/reset-password/{token}",name="resetPassword")
     *
     * @param Request $request
     * @param $token
     * @return RedirectResponse|Response
     */
    public function resetPassword(Request $request, $token)
    {
        $tokenGenerator = $this->get('ns_token.generator');
        try {
            list($id, $email) = $tokenGenerator->decryptToken($token);
        } catch (InvalidTokenException $exception) {
            $this->get('ns_flash')->addError('Error', 'Invalid token');
            return $this->redirect($this->generateUrl('login'));
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityMgr = $this->get('doctrine.orm.entity_manager');
            $user = $entityMgr->find(User::class, $id);
            if ($user && $user->getEmail() == $email) {
                $newPassword = $form->get('password')->getData();
                $encoder = $this->get('security.encoder_factory')->getEncoder($user);
                $user->setPassword($encoder->encodePassword($newPassword,$user->getSalt()));
                $entityMgr->persist($user);
                $entityMgr->flush();

                $this->get('ns_flash')->addSuccess('Updated','Your password has been changed');

                return $this->redirect($this->generateUrl('login'));
            }
        }

        return $this->render('NSSentinelBundle:Security:reset-password.html.twig', ['form' => $form->createView(), 'token' => $token]);
    }

    /**
     * @Route("/{_locale}",name="homepage")
     * @Method(methods={"GET"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homepageAction(Request $request)
    {
        $repo = $this->get('doctrine.orm.entity_manager')->getRepository("NSSentinelBundle:IBD");
        $byCountry = $repo->getByCountry();
        $bySite = $repo->getBySite();
        $byDiagnosis = $repo->getByDiagnosis();
        $form = $this->createForm(ReportFilterType::class, null, ['site_type' => 'advanced']);
        $report = $this->get('ns_sentinel.ibd_report');
        $cResult = $report->getCulturePositive($request, $form, 'homepage');

        return $this->render('NSSentinelBundle:Security:homepage.html.twig', [
            'byCountry' => $byCountry,
            'bySite' => $bySite,
            'byDiagnosis' => $byDiagnosis,
            'cResult' => $cResult['results']]);
    }

    /**
     * @Route("/",name="homepage_redirect")
     * @Method(methods={"GET"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function homepageRedirectAction(Request $request)
    {
        return $this->get('ns.sentinel.services.homepage')->getHomepageResponse($request);
    }
}
