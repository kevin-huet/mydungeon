<?php


namespace App\Controller\Account;


use App\Entity\BlizzardUser;
use App\Entity\User;
<<<<<<< HEAD
use App\Entity\WoW\Character;
=======
use App\Entity\WoW\WarcraftCharacter;
use App\Repository\CharacterRepository;
>>>>>>> release/0.1.0
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
<<<<<<< HEAD

    public function __construct(EntityManagerInterface $entityManager, WarcraftApiService $warcraftApiService,
                                BlizzardApiService $blizzardApiService, ParameterBagInterface $bag)
=======
    /**
     * @var CharacterRepository
     */
    private $characterRepo;

    public function __construct(EntityManagerInterface $entityManager, WarcraftApiService $warcraftApiService,
                                BlizzardApiService $blizzardApiService, ParameterBagInterface $bag, CharacterRepository $characterRepo)
>>>>>>> release/0.1.0
    {
        $this->blizzardApi = $blizzardApiService;
        $this->warcraftApi = $warcraftApiService;
        $this->bag = $bag;
        $this->em = $entityManager;
<<<<<<< HEAD
=======
        $this->characterRepo = $characterRepo;
>>>>>>> release/0.1.0
    }

    /**
     * @Route("/", name="app_sync_account")
     * @return RedirectResponse
     */
    public function syncAccount()
    {
<<<<<<< HEAD
        $valid = false;
        if ($this->getUser() && $this->getUser()->getTokenApi()) {
            if (!$this->blizzardApi->verifyToken($this->getUser()->getTokenApi())) {
                $valid = true;
            }
        }
        return $this->redirect("https://eu.battle.net/oauth/authorize?client_id=" . $this->bag->get('api.key') . "&redirect_uri=" .
            "http://localhost" . $this->generateUrl('app_sync_callback', array("token" => ($valid) ? 1 : 0)) . "&response_type=code&scope=wow.profile");
=======
        /** @var User $user */
        $user = $this->getUser();
        $valid = 0;
        if ($user && $user->getBlizzardUser() && $user->getBlizzardUser()->getToken()) {
            if ($this->blizzardApi->verifyToken($user->getBlizzardUser()->getToken())) {
                $valid = 1;
            }
        }
        return $this->redirect("https://eu.battle.net/oauth/authorize?client_id=" . $this->bag->get('api.key') . "&redirect_uri=" .
            "http://localhost" . $this->generateUrl('app_sync_callback', array("token" => $valid)). "&response_type=code&scope=wow.profile");
>>>>>>> release/0.1.0
    }

    /**
     * @param Request $request
     * @param $token
     * @return RedirectResponse
     * @Route("/callback/{token}", name="app_sync_callback", methods={"GET"})
     */
    public function syncAccountCallback(Request $request, $token)
    {
<<<<<<< HEAD
        if (!$token) {
            $uri = "http://localhost" . $this->generateUrl('callback_api',
                    ["token" => $token]);
            $code = serialize($request->query->get('code'));
            $code = unserialize($code);
            $response = $this->blizzardApi->userAuthorization($code, $uri);
=======
        /** @var User $user */
        $user = $this->getUser();
        if ($user && !$token) {
            $uri = "http://localhost" . $this->generateUrl('app_sync_callback',
                    ["token" => $token]);
            $code = serialize($request->query->get('code'));
            $code = unserialize($code);
            $response = $this->blizzardApi->userAuthorization($uri, $code);
>>>>>>> release/0.1.0
            $response = explode(",", $response);
            $response = explode(":", $response[0]);
            $response = $response[1];
            $response = str_replace('"', '', $response);
<<<<<<< HEAD
            echo $response;
            return;
        }
        return $this->redirectToRoute('app_home');
    }

    public function saveUserInformation($userData)
    {

    }
=======

            if (!$user->getBlizzardUser()) {
                $blizzardUser = new BlizzardUser();
                $user->setBlizzardUser($blizzardUser);
                $blizzardUser->setUser($user);
                $this->em->persist($blizzardUser);
            }
            /** @var BlizzardUser $blizzardUser */
            $blizzardUser = $user->getBlizzardUser();
            $blizzardUser->setToken($response);
            $this->em->persist($user);
            $this->em->persist($blizzardUser);
            $this->em->flush();

        }
        $this->saveCharacters($user->getBlizzardUser());
        return $this->redirectToRoute('app_home');
    }



>>>>>>> release/0.1.0

    public function saveCharacters(BlizzardUser $user)
    {
        if ($user) {
            $result = $this->warcraftApi->getCharacters($user->getToken());
<<<<<<< HEAD
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
=======
            echo $result;
            $result = json_decode($result, true);
            foreach ($result['wow_accounts'][0]['characters'] as $character) {
                if ($character['level'] == 50 && !$this->checkIfCharacterExist($user, $character)) {
                    $newCharacter = new WarcraftCharacter();
                    $newCharacter->setName($character['name']);
                    $newCharacter->setPlayableClass($character['playable_class']['id']);
                    $newCharacter->setRealm($character['realm']['slug']);
                    $newCharacter->setBlizzardUser($user);
                    $user->addCharacter($newCharacter);
                    $this->em->persist($newCharacter);
                }
            }
            $this->em->persist($user);
            $this->em->flush();
        }
    }

    public function checkIfCharacterExist(BlizzardUser $blizzardUser, $characterResult)
    {
        foreach ($blizzardUser->getCharacters() as $character) {
            if ($characterResult['name'] == $character->getName() && $characterResult['realm']['slug'] == $character->getRealm())
                return true;
        }
        return false;
>>>>>>> release/0.1.0
    }
}