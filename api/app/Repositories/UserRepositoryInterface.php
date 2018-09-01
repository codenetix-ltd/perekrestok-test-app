<?php namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface extends CRUDRepositoryInterface
{
    /**
     * @param integer $externalId
     * @return Model|null
     */
    public function findByExternalId(int $externalId): ?Model;
}
