<?php


namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class AdminUsersController
 * @Route("/admin")
 * @Security("is_granted('ROLE_ADMIN')")
 * @package App\Controller\Admin
 */
class AdminUsersController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserRepository $repository, UserService $userService) {
        $this->userRepository = $repository;
        $this->userService = $userService;
    }
    
    /**
     * @Route("/users", name="app_admin_users")
     */
    public function showAllUsers() {
        $users = $this->userRepository->findAll();
        return $this->render('admin/users/admin_users.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/user/{user}", name="app_admin_show_user")
     * @param User $user
     * @return Response
     */
    public function showSpecificUser(User $user) {
        return $this->render('admin/users/admin_show_specific_user.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/{user}/role", name="app_admin_user_role")
     * @param User $user
     * @return Response
     */
    public function changeUserRole(User $user) {
        return $this->render('admin/users/', [

        ]);
    }

    /**
     * @Route("/user/{user}/edit", name="app_admin_user_edit")
     * @param User $user
     * @return Response
     */
    public function editUser(User $user) {
        return $this->render('admin/users/', [

        ]);
    }

    /**
     * @Route("/user/{user}/delete", name="app_admin_user_delete")
     * @param User $user
     * @return void
     */
    public function deleteUser(User $user) {
        $this->userService->delete($user);
        $this->redirectToRoute('app_admin_users');
    }

    /**
     * @Route("/user/{user}/punishment", name="app_admin_user_punishment")
     * @param User $user
     * @return Response
     */
    public function userPunishment(User $user) {
        return $this->render('admin/users/', [

        ]);
    }
}