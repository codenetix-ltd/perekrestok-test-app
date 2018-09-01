<?php

namespace App\Services\Clients\UserExternalServiceClient;

class UserExternalServiceClientMock implements UserExternalServiceClientInterface
{
    /**
     * @var array
     */
    private $fakerUsers = [
        1 => [
            'id' => 1,
            'name' => 'Joe',
            'email' => 'joe@mail.com'
        ],
        2 => [
            'id' => 2,
            'name' => 'Alice',
            'email' => 'al@mail.com'
        ],
        3 => [
            'id' => 3,
            'name' => 'Bob',
            'email' => 'bob@mail.com'
        ]
    ];

    /**
     * @param integer $id
     * @return ExternalUser
     */
    public function getUserById(int $id): ExternalUser
    {
        if (!array_key_exists($id, $this->fakerUsers)) {
            throw new ExternalUserNotFoundException('External user service: User with id '.$id.' was not found');
        }

        return (new RawToObjectExternalUserTransformer())->transform($this->fakerUsers[$id]);
    }
}
