<?php namespace App\Repositories;

use App\QueryParams\IQueryParamsObject;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface CRUDRepositoryInterface
{

    /**
     * @param IQueryParamsObject $queryParamsObject Query params to apply for the result set.
     * @return LengthAwarePaginator
     */
    public function paginateList(IQueryParamsObject $queryParamsObject): LengthAwarePaginator;

    /**
     * @param array $data Array representation of saving model.
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * @param integer $id   ID of updating model.
     * @param array   $data Array representation of updating model.
     * @return Model
     */
    public function update(int $id, array $data): Model;

    /**
     * @param integer $id ID of finding model.
     * @return Model
     *
     * @throws ModelNotFoundException
     */
    public function find(int $id): Model;

    /**
     * @param array $whereClauses Array of parameters to filter result.
     * @return Model
     */
    public function findWhere(array $whereClauses): ?Model;

    /**
     * @param integer $id   ID of deleting model.
     * @param boolean $soft
     * @return boolean
     */
    public function delete(int $id, bool $soft = false): bool;

    /**
     * @return Collection Collection of all retrieved models from the database.
     */
    public function all(): Collection;
}
