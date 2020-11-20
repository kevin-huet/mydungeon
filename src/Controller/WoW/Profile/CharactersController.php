<?php


namespace App\Controller\WoW\Profile;


use App\Entity\BlizzardUser;
use App\Entity\User;
use App\Service\Api\RaiderioApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CharactersController
 * @package App\Controller\WoW\Profile
 * @Route("/character")
 */
class CharactersController extends AbstractController
{
    /**
     * @var RaiderioApiService
     */
    private $raiderApi;

    public function __construct(RaiderioApiService $raiderioApiService)
    {
        $this->raiderApi = $raiderioApiService;
    }

    /**
     * @Route("/", name="app_character_all")
     * @return Response
     */
    public function showAllCharacters()
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var BlizzardUser $blizzardUser */
        $blizzardUser = $user->getBlizzardUser();
        $characters = [];

        foreach ($blizzardUser->getCharacters() as $character) {
            $characters[] = $this->raiderApi->getCharacter($character->getName(), $character->getRealm(), 'eu');
        }

        return $this->render('wow/show_all_characters.html.twig', ['characters' => $characters]);
    }

    /**
     * @param string $realm
     * @param string $username
     * @Route("/{realm}/{username}/", name="app_character")
     * @return Response
     */
    public function showCharacter(string $realm, string $username)
    {
        $character = $this->raiderApi->getCharacter($username, $realm, 'eu');
        return $this->render('wow/show_character.html.twig', ['character' => $character]);
    }
}