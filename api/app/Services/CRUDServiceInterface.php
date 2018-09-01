<?php
namespace App\Services;

use App\QueryParams\IQueryParamsObject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface CRUDServiceInterface
{
    /**
     * @param IQueryParamsObject $queryParamsObject
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(IQueryParamsObject $queryParamsObject);

    /**
     * @param integer $id
     * @return Model
     */
    public function find(int $id): Model;

    /**
     * @return Collection
     */
    public function list(): Collection;

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * @param integer $id
     * @param array   $data
     * @return Model
     */
    public function update(int $id, array $data): Model;

    /**
     * @param integer $id
     * @param boolean $soft
     * @return boolean
     */
    public function delete(int $id, bool $soft = false): ?bool;
}
