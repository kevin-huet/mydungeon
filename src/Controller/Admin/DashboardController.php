<?php

namespace App\Controller\Admin;

use App\Entity\BlizzardUser;
use App\Entity\ClassInfo;
use App\Entity\DungeonData;
use App\Entity\User;
use App\Entity\WoW\DungeonGroup;
use App\Entity\WoW\Expansion;
use App\Entity\WoW\GameInfo;
use App\Entity\WoW\WarcraftCharacter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class DashboardController
 * @Route("/admin", name="admin")
 * @IsGranted("ROLE_ADMIN")
 * @package App\Controller\Admin
 */
class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/", name="app_admin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(UserCrudController::class)->generateUrl());
    }

    /**
     * @Route("/game_info", name="app_admin_game")
     */
    public function gameInfo(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(GameInfoCrudController::class)->generateUrl());
    }


    /**
     * @Route("/group", name="app_admin_group")
     */
    public function group(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(DungeonGroupCrudController::class)->generateUrl());

    }

    /**
     * @Route("/blizzard_user", name="app_admin_group")
     */
    public function blizzardUser(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(BlizzardUserCrudController::class)->generateUrl());

    }

    /**
     * @Route("/character", name="app_admin_group")
     */
    public function character(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(WarcraftCharacterCrudController::class)->generateUrl());

    }

    /**
     * @Route("/class_info", name="app_admin_class")
     */
    public function classInfo(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(ClassInfoCrudController::class)->generateUrl());

    }

    /**
     * @Route("/dungeon", name="app_admin_class")
     */
    public function dungeonData(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(DungeonDataCrudController::class)->generateUrl());

    }

    /**
     * @Route("/expansion", name="app_admin_class")
     */
    public function expansionData(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(ExpansionCrudController::class)->generateUrl());

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            // the name visible to end users
            ->setTitle('MyDungeon Admin')
            // you can include HTML contents too (e.g. to link to an image)
            // the path defined in this method is passed to the Twig asset() function
            ->setFaviconPath('favicon.svg')

            // the domain used by default is 'messages'
            ->setTranslationDomain('my-custom-domain')

            // there's no need to define the "text direction" explicitly because
            // its default value is inferred dynamically from the user locale
            ->setTextDirection('ltr');

            // set this option if you prefer the page content to span the entire
            // browser width, instead of the default design which sets a max width

            // set this option if you prefer the sidebar (which contains the main menu)
            // to be displayed as a narrow column instead of the default expanded design

            // by default, all backend URLs include a signature hash. If a user changes any
            // query parameter (to "hack" the backend) the signature won't match and EasyAdmin
            // triggers an error. If this causes any issue in your backend, call this method
            // to disable this feature and remove all URL signature checks
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('User', 'fa fa-list', User::class);
        yield MenuItem::linkToCrud('Blizzard user', 'fa fa-list', BlizzardUser::class);
        yield MenuItem::linkToCrud('Character', 'fa fa-list', WarcraftCharacter::class);
        yield MenuItem::linkToCrud('Dungeon Group', 'fa fa-list', DungeonGroup::class);
        yield MenuItem::linkToCrud('class info', 'fa fa-list', ClassInfo::class);
        yield MenuItem::linkToCrud('Game info', 'fa fa-list', GameInfo::class);
        yield MenuItem::linkToCrud('Dungeon', 'fa fa-list', DungeonData::class);
        yield MenuItem::linkToCrud('Expansion', 'fa fa-list', Expansion::class);


        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }

}
