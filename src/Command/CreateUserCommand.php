<?php


namespace App\Command;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    protected static $defaultName = 'app:create-user';

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct();

        $this->em = $entityManager;
        $this->encoder = $encoder;
    }

    protected function configure()
    {
        $this
            // configure an argument
            ->addArgument('email', InputArgument::REQUIRED, 'Email of the user.')
            ->addArgument('password', InputArgument::REQUIRED, 'password of the user.')
        ;
    }

// ...
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $user = new User();
        $output->writeln([
            'User Creation',
            '.............',
            '',
        ]);

        $user->setEmail($input->getArgument('email'));
        $encodedPassword = $this->encoder->encodePassword($user, $input->getArgument('password'));
        $user->setPassword($encodedPassword);
        $user->setIsVerified(true);
        $this->em->persist($user);
        $this->em->flush();

        $output->writeln('Done.');

        return Command::SUCCESS;
    }
}