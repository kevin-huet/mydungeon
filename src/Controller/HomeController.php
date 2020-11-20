<?php


namespace App\Controller;


use App\Service\Api\BlizzardApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @var BlizzardApiService
     */
    private $blizzApi;

    public function __construct(BlizzardApiService $apiService)
    {
        $this->blizzApi = $apiService;
    }
    /**
     * @Route("/", name="app_home")
     */
    public function home()
    {
        return $this->render('index.html.twig', []);
    }
}