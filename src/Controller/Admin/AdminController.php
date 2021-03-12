<?php


namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class AdminController
 * @Route("/admin")
 * @package App\Controller\Admin
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/", name="app_admin")
     */
    public function homeAdmin()
    {
        return $this->render('admin/admin_home.html.twig');
    }

    /**
     * @Route("/data", name="app_admin_data")
     */
    public function gameDataAdmin()
    {
        return $this->render('admin/data/admin_data.html.twig');

    }
}