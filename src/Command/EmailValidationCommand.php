<?php


namespace App\Command;


use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EmailValidationCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    protected static $defaultName = 'app:valid-email';
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
        $this->em = $entityManager;
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
        $user = $this->userRepository->findByEmail($input->getArgument('email'));

        if (!$user)
            return Command::FAILURE;

        $output->writeln([
            'Set email validation',
            '.............',
            '',
        ]);

        $user->setIsVerified(true);
        $this->em->persist($user);
        $this->em->flush();

        $output->writeln('Done.');

        return Command::SUCCESS;
    }
}