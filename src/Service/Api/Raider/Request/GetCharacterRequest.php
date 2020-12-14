<?php


namespace App\Service\Api\Raider\Request;


use App\Service\Api\RaiderioApiService;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetCharacterRequest
{

    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $realm;
    /**
     * @var string
     */
    private $region;

    const URL = "https://raider.io/api/v1/";
    /**
     * @var HttpClientInterface
     */
    private $client;

    public function __construct(string $username, string $realm, string $region )
    {
        $this->username = $username;
        $this->realm = $realm;
        $this->region = $region;
        $this->client = HttpClient::create();
    }

    public function send()
    {
        $url = self::URL."characters/profile?region={region}&realm={realm}&name={name}&fields=mythic_plus_scores";
        $url = str_replace(['{region}', '{realm}', '{name}'], [$this->region, $this->realm, $this->username], $url);

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
}