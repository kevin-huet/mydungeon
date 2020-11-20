<?php


namespace App\Service\Api;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RaiderioApiService
{
    const URL = "https://raider.io/api/v1/";

    /**
     * @var HttpClientInterface
     */
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getCharacter(string $username, string $realm, string $region)
    {
        $url = self::URL."characters/profile?region={region}&realm={realm}&name={name}&fields=mythic_plus_scores";
        $url = str_replace(['{region}', '{realm}', '{name}'], [$region, $realm, $username], $url);

        try {
            $response = $this->client->request('GET', $url);
            return $response->toArray();
        } catch (TransportExceptionInterface $e) {
        } catch (ClientExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (DecodingExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        }
        return false;
    }

    public function getAffixes(string $region, string $locale)
    {
        $url = self::URL."mythic-plus/affixes?region={region}&locale={locale}";
        $url = str_replace(['{region}', '{locale}'], [$region, $locale], $url);

        try {
            $response = $this->client->request('GET', $url);
            return $response->toArray();
        } catch (TransportExceptionInterface $e) {
        } catch (ClientExceptionInterface $e) {
        } catch (DecodingExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        }
        return false;
    }

    public function getScoreTiers()
    {
        $url = self::URL."mythic-plus/score-tiers";

        try {
            $response = $this->client->request('GET', $url);
            return $response->toArray();
        } catch (ClientExceptionInterface $e) {
        } catch (DecodingExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        }
        return false;
    }
}