<?php


namespace App\Service\Api;


use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BlizzardApiService
{
    /**
     * @var ParameterBagInterface
     */
    private $bag;
    /**
     * @var HttpClientInterface
     */
    private $client;

    public function __construct(ParameterBagInterface $bag, HttpClientInterface $client)
    {
        $this->bag = $bag;
        $this->client = $client;
    }


    public function sendRequest(string $apiUrl="", $token ="", array $param = [], $region = "eu")
    {
        try {
            $token = $this->accessToken();
            if (!$token)
                return null;
            $token = $token->access_token;
        } catch (Exception $e) {
            return $e->getMessage();
        }
        $param = implode($param);
        $client = HttpClient::create();
        $url = "https://" . $region . ".api.blizzard.com/" . $apiUrl . "?" . $param . "&access_token=" . $token;

        try {
            $response = $client->request('GET', $url);
        } catch (TransportExceptionInterface $e) {
            return null;
        }
        try {
            return $response->toArray();
        } catch (ClientExceptionInterface $e) {
        } catch (DecodingExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        }
        return null;

    }

    protected function accessToken()
    {
        try {
            $response = $this->client->request('POST', 'https://eu.battle.net/oauth/token', [
                'auth_basic' => [$this->bag->get('api.key'), $this->bag->get('api.secret')],
                'body' => ['grant_type' => 'client_credentials'],
            ]);
            return json_decode($response->getContent());
        } catch (TransportExceptionInterface $e) {
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        }

        return false;
    }

    public function verifyToken($token)
    {
        if (!$token)
            return true;
        try {
            $result = $this->client->request('POST', 'https://eu.battle.net/oauth/check_token', [
                'auth_basic' => [$this->bag->get('api.key'), $this->bag->get('api.secret')],
                'query' => ['token' => $token],
            ]);
            $result = $result->getContent();
        } catch (TransportExceptionInterface $e) {
            return false;
        } catch (ClientExceptionInterface $e) {
            return false;
        } catch (RedirectionExceptionInterface $e) {
            return false;
        } catch (ServerExceptionInterface $e) {
            return false;
        }
        if (!strpos($result, "error") && !strpos($result, "invalid"))
            return true;
        return false;
    }

    public function userAuthorization($uri, $code)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://eu.battle.net/oauth/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "redirect_uri=".$uri."&scope=wow.profile&grant_type=authorization_code&code=".$code);
        curl_setopt($ch, CURLOPT_USERPWD, $this->bag->get('api.key'). ':' . $this->bag->get('api.secret'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

    public function userGetInfo(string $code)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://eu.battle.net/oauth/userinfo');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'Authorization: Bearer '.$code;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

    public function testApi()
    {
    }

}