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
            $result = $this->client->request('GET', 'https://eu.api.blizzard.com/profile/user/wow', [
                'query' => ['access_token' => $token, 'namespace' => 'profile-eu', 'locale' => 'fr_FR'],
            ]);
            return $result->getContent();
    }
}