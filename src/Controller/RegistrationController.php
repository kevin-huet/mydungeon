<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\DungeonGroupRequestType;
use App\Form\RegistrationFormType;
use App\Service\Mail\EmailVerifyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @var EmailVerifyService
     */
    private $emailVerifyService;

    public function __construct(EmailVerifyService $emailVerifyService)
    {
        $this->emailVerifyService = $emailVerifyService;
    }

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setVerifyToken(md5(uniqid($user->getEmail(), true)));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $render = $this->renderView(
                'mail/mail_confirmation.html.twig',
                ['userId' => $user->getId(), 'token' => $user->getVerifyToken()]);
            $this->emailVerifyService->sendMailVerification($render, $user->getEmail());
            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/register/verify/{user}/{token}", name="app_registration_verify")
     * @param User $user
     * @param string $token
     * @return RedirectResponse
     */
    public function registerVerify(User $user, string $token)
    {
        if ($user->getVerifyToken() == $token) {
            $user->setIsVerified(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_home');
    }
}
