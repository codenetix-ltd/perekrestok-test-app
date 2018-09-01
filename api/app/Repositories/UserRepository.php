<?php

namespace App\Repositories;

use App\Event;
use App\QueryObject\EventListQueryObject;
use App\QueryParams\IQueryParamsObject;
use App\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class UserRepository implements UserRepositoryInterface
{
    use CRUDRepositoryTrait;

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getInstance()
    {
        return new User();
    }

    /**
     * @param integer $externalId
     * @return Model
     */
    public function findByExternalId(int $externalId): ?Model
    {
        return $this->getInstance()->where('external_user_id', $externalId)->first();
    }
}
