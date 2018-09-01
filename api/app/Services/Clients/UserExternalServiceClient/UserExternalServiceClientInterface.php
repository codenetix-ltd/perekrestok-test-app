<?php
namespace App\Services\Clients\UserExternalServiceClient;

interface UserExternalServiceClientInterface
{
    /**
     * @param integer $id
     * @return ExternalUser
     */
    public function getUserById(int $id) : ExternalUser;
}
