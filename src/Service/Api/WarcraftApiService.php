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
}