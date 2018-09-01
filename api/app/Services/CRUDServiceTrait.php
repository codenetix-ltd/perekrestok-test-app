<?php
namespace App\Services;

use App\QueryParams\IQueryParamsObject;
use App\Repositories\CRUDRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait CRUDServiceTrait
{
    /**
     * @var CRUDRepositoryInterface
     */
    private $repository;

    /**
     * @param CRUDRepositoryInterface $repository
     * @return void
     */
    public function setRepository(CRUDRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param IQueryParamsObject $queryParamsObject
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(IQueryParamsObject $queryParamsObject)
    {
        return $this->repository->paginateList($queryParamsObject);
    }

    /**
     * @param integer $id
     * @return Model
     */
    public function find(int $id): Model
    {
        return $this->repository->find($id);
    }

    /**
     * @return Collection
     */
    public function list(): Collection
    {
        return $this->repository->all();
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        /** @var Model $model */
        return $this->repository->create($data);
    }

    /**
     * @param integer $id
     * @param array   $data
     * @return Model
     */
    public function update(int $id, array $data): Model
    {
        /** @var Model $model */
        return $this->repository->update($id, $data);
    }

    /**
     * @param integer $id
     * @param boolean $soft
     * @return boolean
     */
    public function delete(int $id, bool $soft = false): ?bool
    {
        return $this->repository->delete($id, $soft);
    }
}
