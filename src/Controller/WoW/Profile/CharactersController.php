<?php


namespace App\Controller\WoW\Profile;


use App\Entity\BlizzardUser;
use App\Entity\User;
use App\Repository\DungeonDataRepository;
use App\Service\Api\RaiderioApiService;
use App\Service\Api\WarcraftApiService;
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
    /**
     * @var DungeonDataRepository
     */
    private $dungeonRepository;
    /**
     * @var WarcraftApiService
     */
    private $warcraftApi;

    public function __construct(RaiderioApiService $raiderioApiService, DungeonDataRepository $dungeonRepository,
        WarcraftApiService $warcraftApiService)
    {
        $this->raiderApi = $raiderioApiService;
        $this->dungeonRepository = $dungeonRepository;
        $this->warcraftApi = $warcraftApiService;
    }

    /**
     * @Route("/", name="app_character_all")
     * @return Response
     */
    public function showAllCharacters(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var BlizzardUser $blizzardUser */
        $blizzardUser = $user->getBlizzardUser();
        $characters = [];
        $scoreTier = $this->raiderApi->getScoreTiers();

        foreach ($blizzardUser->getCharacters() as $character) {
            $temp = $this->raiderApi->getCharacter($character->getName(), $character->getRealm(), 'eu');
            $temp['scoreColor'] = $this->setColorByScore($temp['mythic_plus_scores']['all'], $scoreTier);
            $characters[] = $temp;
        }

        return $this->render('wow/show_all_characters.html.twig', [
            'characters' => $characters,
        ]);
    }

    private function setColorByScore($score, $colors): string
    {
        foreach ($colors as $color) {
            if ($score >= $color['score']) {
                return $color['rgbHex'];
            }
        }
        return 'white';
    }

    /**
     * @param string $realm
     * @param string $username
     * @Route("/{realm}/{username}/", name="app_character")
     * @return Response
     */
    public function showCharacter(string $realm, string $username): Response
    {
        $scoreTier = $this->raiderApi->getScoreTiers();
        $character = $this->raiderApi->getCharacter($username, $realm, 'eu');
        $character['scoreColor'] = $this->setColorByScore($character['mythic_plus_scores']['all'], $scoreTier);
        $dungeon = $this->dungeonRepository->findDungeonLastExpansion(true);

        $spe = $this->warcraftApi->getCharacterSpecialization($realm, $username);
        $traits = $this->warcraftApi->getPlayerCovenantsTraits($realm, $username);
        $spe = json_decode($spe, true);
        return $this->render('wow/show_character.html.twig', ['character' => $character, 'dungeons' => $dungeon,
            'talents' => $spe["specializations"][0]["talents"], 'traits' => $traits
        ]);
    }
}