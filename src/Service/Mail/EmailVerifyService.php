<?php


namespace App\Service\Mail;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EmailVerifyService
{
    /**
     * @var EmailService
     */
    private $emailService;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var ParameterBagInterface
     */
    private $bag;

    public function __construct(EmailService $emailService, EntityManagerInterface $entityManager, \Swift_Mailer $mailer, ParameterBagInterface $bag)
    {
        $this->emailService = $emailService;
        $this->em = $entityManager;
        $this->mailer = $mailer;
        $this->bag = $bag;
    }

    public function sendMailVerification($render, $email)
    {
        $message = (new \Swift_Message())
            ->setFrom($this->bag->get('email'))
            ->setTo($email)
            ->setBody(
                $render, 'text/html'
            );
        $this->mailer->send($message);
    }

}