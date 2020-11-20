<?php

namespace App\Controller;

use App\Entity\User;
<<<<<<< HEAD
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\SecurityAthenticatorAuthenticator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
=======
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
>>>>>>> release/0.1.0
    }

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
<<<<<<< HEAD
     * @param GuardAuthenticatorHandler $guardHandler
     * @param SecurityAthenticatorAuthenticator $authenticator
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, SecurityAthenticatorAuthenticator $authenticator): Response
=======
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
>>>>>>> release/0.1.0
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
<<<<<<< HEAD

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('kevin.huet22330@gmail.com', 'Verify Account'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
=======
            $user->setVerifyToken(md5(uniqid($user->getEmail(), true)));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $render = $this->renderView(
                'mail/mail_confirmation.html.twig',
                ['userId' => $user->getId(), 'token' => $user->getVerifyToken()]);
            $this->emailVerifyService->sendMailVerification($render, $user->getEmail());
            return $this->redirectToRoute('app_home');
>>>>>>> release/0.1.0
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
<<<<<<< HEAD
     * @Route("/verify/email", name="app_verify_email")
     * @param Request $request
     * @return Response
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_home');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

=======
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
>>>>>>> release/0.1.0
        return $this->redirectToRoute('app_home');
    }
}
