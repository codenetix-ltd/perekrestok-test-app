<?php

namespace App\Services\Clients\UserExternalServiceClient;

class RawToObjectExternalUserTransformer
{
    /**
     * @param array $rawData
     * @return ExternalUser
     */
    public function transform(array $rawData): ExternalUser
    {
        $externalUser = new ExternalUser();
        $externalUser->setId($rawData['id']);
        $externalUser->setName($rawData['name']);
        $externalUser->setEmail($rawData['email']);

        return $externalUser;
    }
}
