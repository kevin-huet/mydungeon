<?php


namespace App\Controller\Account;


use App\Entity\BlizzardUser;
use App\Entity\User;
use App\Entity\WoW\WarcraftCharacter;
use App\Repository\CharacterRepository;
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
    private $warcraftApi;
    /**
     * @var CharacterRepository
     */
    private $characterRepo;

    public function __construct(EntityManagerInterface $entityManager, WarcraftApiService $warcraftApiService,
                                BlizzardApiService $blizzardApiService, ParameterBagInterface $bag, CharacterRepository $characterRepo)
    {
        $this->blizzardApi = $blizzardApiService;
        $this->warcraftApi = $warcraftApiService;
        $this->bag = $bag;
        $this->em = $entityManager;
        $this->characterRepo = $characterRepo;
    }

    /**
     * @Route("/", name="app_sync_account")
     * @return RedirectResponse
     */
    public function syncAccount()
    {
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
    }

    /**
     * @param Request $request
     * @param $token
     * @return RedirectResponse
     * @Route("/callback/{token}", name="app_sync_callback", methods={"GET"})
     */
    public function syncAccountCallback(Request $request, $token)
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user && !$token) {
            $uri = "http://localhost" . $this->generateUrl('app_sync_callback',
                    ["token" => $token]);
            $code = serialize($request->query->get('code'));
            $code = unserialize($code);
            $response = $this->blizzardApi->userAuthorization($uri, $code);
            $response = explode(",", $response);
            $response = explode(":", $response[0]);
            $response = $response[1];
            $response = str_replace('"', '', $response);

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




    public function saveCharacters(BlizzardUser $user)
    {
        if ($user) {
            $result = $this->warcraftApi->getCharacters($user->getToken());
            $result = json_decode($result, true);
            foreach ($result['wow_accounts'][0]['characters'] as $character) {
                if ($character['level'] >= 50 && !$this->checkIfCharacterExist($user, $character)) {
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
    }
}