<?php


namespace App\Controller\Account;


use App\Entity\BlizzardUser;
use App\Entity\User;
use App\Entity\WoW\Character;
use App\Service\Api\BlizzardApiService;
use App\Service\Api\WarcraftApiService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SyncAccountController
 * @package App\Controller\Account
 * @Route("/account/sync")
 */
class SyncAccountController extends AbstractController
{
    /**
     * @var BlizzardApiService
     */
    private $blizzardApi;
    /**
     * @var ParameterBagInterface
     */
    private $bag;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var WarcraftApiService
     */
    private $WarcraftApi;
    /**
     * @var WarcraftApiService
     */
    private $warcraftApi;

    public function __construct(EntityManagerInterface $entityManager, WarcraftApiService $warcraftApiService,
                                BlizzardApiService $blizzardApiService, ParameterBagInterface $bag)
    {
        $this->blizzardApi = $blizzardApiService;
        $this->warcraftApi = $warcraftApiService;
        $this->bag = $bag;
        $this->em = $entityManager;
    }

    /**
     * @Route("/", name="app_sync_account")
     * @return RedirectResponse
     */
    public function syncAccount()
    {
        $valid = false;
        if ($this->getUser() && $this->getUser()->getTokenApi()) {
            if (!$this->blizzardApi->verifyToken($this->getUser()->getTokenApi())) {
                $valid = true;
            }
        }
        return $this->redirect("https://eu.battle.net/oauth/authorize?client_id=" . $this->bag->get('api.key') . "&redirect_uri=" .
            "http://localhost" . $this->generateUrl('app_sync_callback', array("token" => ($valid) ? 1 : 0)) . "&response_type=code&scope=wow.profile");
    }

    /**
     * @param Request $request
     * @param $token
     * @return RedirectResponse
     * @Route("/callback/{token}", name="app_sync_callback", methods={"GET"})
     */
    public function syncAccountCallback(Request $request, $token)
    {
        if (!$token) {
            $uri = "http://localhost" . $this->generateUrl('callback_api',
                    ["token" => $token]);
            $code = serialize($request->query->get('code'));
            $code = unserialize($code);
            $response = $this->blizzardApi->userAuthorization($code, $uri);
            $response = explode(",", $response);
            $response = explode(":", $response[0]);
            $response = $response[1];
            $response = str_replace('"', '', $response);
            echo $response;
            return;
        }
        return $this->redirectToRoute('app_home');
    }

    public function saveUserInformation($userData)
    {

    }

    public function saveCharacters(BlizzardUser $user)
    {
        if ($user) {
            $result = $this->warcraftApi->getCharacters($user->getToken());
            //foreach ($result['wow_accounts'][0]['characters'] as $character) {
                //if ($character['level'] == 120 && $this->checkIfCharacterExist($character, $user->getCharacters())) {

               // }
            }

    }

    public function checkIfCharacterExist($character, ArrayCollection $characters = null)
    {
        if (!$characters)
            return false;
        return true;
    }
}