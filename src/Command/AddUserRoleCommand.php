<?php


namespace App\Command;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddUserRoleCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    protected static $defaultName = 'app:user:set-role';
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
            ->addArgument('role', InputArgument::REQUIRED, 'Role needed')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var User $user */
        $user = $this->userRepository->findByEmail($input->getArgument('email'));
        $role = $input->getArgument('role');

        if ($role != "ROLE_USER" && $role != "ROLE_ADMIN" && $role != "ROLE_MOD")
            return Command::FAILURE;
        if (!$user)
            return Command::FAILURE;

        $output->writeln([
            'Change role',
            '.............',
            '',
        ]);

        $user->setRoles([$role]);
        $this->em->persist($user);
        $this->em->flush();

        $output->writeln('Done.');

        return Command::SUCCESS;
    }
}