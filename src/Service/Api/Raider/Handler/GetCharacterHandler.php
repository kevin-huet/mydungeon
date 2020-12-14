<?php


namespace App\Service\Api\Raider\Handler;


use App\Service\Api\Raider\Request\GetCharacterRequest;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetCharacterHandler implements MessageHandlerInterface
{
    public function __construct()
    {

    }

    public function __invoke(GetCharacterRequest $request)
    {
        $response = $request->send();
        if ($response)
            return $response;
        return null;
    }


}