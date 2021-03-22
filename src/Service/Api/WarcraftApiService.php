<?php


namespace App\Service\Api;


use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WarcraftApiService
{
    /**
     * @var BlizzardApiService
     */
    private $blizzardApi;
    /**
     * @var HttpClientInterface
     */
    private $client;

    public function __construct(BlizzardApiService $blizzardApi, HttpClientInterface $client)
   {
       $this->blizzardApi = $blizzardApi;
       $this->client = $client;
   }



    public function getCharacters(string $token)
    {
        if (!$token)
            return false;
        try {
            $result = $this->client->request('GET', 'https://eu.api.blizzard.com/profile/user/wow', [
                'query' => ['access_token' => $token, 'namespace' => 'profile-eu', 'locale' => 'en_GB'],
            ]);
            return $result->getContent();
        } catch (TransportExceptionInterface $e) {
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        }
        return null;
    }

    public function getExpansions()
    {
        $token = $this->blizzardApi->accessToken()->access_token;
            $result = $this->client->request('GET', 'https://eu.api.blizzard.com/data/wow/journal-expansion/index', [
                'query' => ['access_token' => $token, 'namespace' => 'static-eu', 'locale' => 'en_GB'],
            ]);
            return $result->getContent();

    }

    public function getInstanceExpansions($id)
    {
        $token = $this->blizzardApi->accessToken()->access_token;

        try {
            $result = $this->client->request('GET', 'https://eu.api.blizzard.com/data/wow/journal-expansion/' . $id, [
                'query' => ['access_token' => $token, 'namespace' => 'static-eu', 'locale' => 'en_GB'],
            ]);
            return $result->getContent();
        } catch (TransportExceptionInterface $e) {
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        }
        return null;
    }

    public function getInstanceMedia($id)
    {
        $token = $this->blizzardApi->accessToken()->access_token;

        try {
            $result = $this->client->request('GET', 'https://eu.api.blizzard.com/data/wow/media/journal-instance/' . $id, [
                'query' => ['access_token' => $token, 'namespace' => 'static-eu', 'locale' => 'en_GB'],
            ]);
            return $result->getContent();
        } catch (TransportExceptionInterface $e) {
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        }
        return null;
    }

    public function getCharacterSpecialization(string $realm, string $username)
    {
        $token = $this->blizzardApi->accessToken()->access_token;

            $result = $this->client->request('GET', 'https://eu.api.blizzard.com/profile/wow/character/'.strtolower($realm).'/'.strtolower($username).'/specializations', [
                'query' => ['access_token' => $token, 'namespace' => 'profile-eu', 'locale' => 'en_GB'],
            ]);
            //echo $result->getContent();
            return $result->getContent();

    }

    public function getCharacterProfile(string $realm, string $username, string $token = "")
    {
        if ($token == "")
            $token = $this->blizzardApi->accessToken()->access_token;
        $url = "https://eu.api.blizzard.com/profile/wow/character/".$realm.'/'.$username;
        try {
            $result = $this->client->request('GET', $url, [
                'query' => ['access_token' => $token, 'namespace' => 'profile-eu', 'locale' => 'en_GB'],
            ]);
            return json_decode($result->getContent());
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        }
    }

    public function getPlayerCovenantsTraits(string $realm, string $username)
    {
        $token = $this->blizzardApi->accessToken()->access_token;
        $characterData = $this->getCharacterProfile($realm, $username, $token);
        $soulbindsUrl = $characterData->covenant_progress->soulbinds->href;

        $result = $this->sendRequestByProfile($soulbindsUrl, $token);
        //echo json_encode($result);
        $spellId =[];
        foreach ($result->soulbinds as $soulbind) {
            if (isset($soulbind->is_active)) {

                foreach ($soulbind->traits as $trait) {
                    if (isset($trait->trait)) {
                        $trait = $this->sendRequestByStatic($trait->trait->key->href, $token);
                        $spellId[] = $trait->spell_tooltip->spell->id;
                    }
                }

            }
        }
        return $spellId;
    }

    public function sendRequestByProfile($url, $token)
    {
        try {
            $result = $this->client->request('GET', $url, [
            'query' => ['access_token' => $token, 'namespace' => 'profile-eu', 'locale' => 'en_GB'],
            ]);
            return json_decode($result->getContent());
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        }
        return null;
    }

    public function sendRequestByStatic($url, $token)
    {
        try {
            $result = $this->client->request('GET', $url, [
                'query' => ['access_token' => $token, 'namespace' => 'static-eu', 'locale' => 'en_GB'],
            ]);
            return json_decode($result->getContent());
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        }
        return null;
    }
}