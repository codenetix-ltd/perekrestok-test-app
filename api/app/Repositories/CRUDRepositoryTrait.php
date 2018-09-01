<?php namespace App\Repositories;

use App\QueryParams\IQueryParamsObject;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait CRUDRepositoryTrait
{
    /**
     * @return Model
     */
    abstract protected function getInstance();

    /**
     * @param IQueryParamsObject $queryParamsObject Query params to apply for the result set.
     * @return LengthAwarePaginator
     */
    public function paginateList(IQueryParamsObject $queryParamsObject): LengthAwarePaginator
    {
        $paginationParameters = $queryParamsObject->getPaginationData();

        return $this->getInstance()->paginate($paginationParameters['perPage'], ['*'], 'page', $paginationParameters['page']);
    }

    /**
     * @param array $data Array representation of saving model.
     * @return Model
     */
    public function create(array $data): Model
    {
        $entry = $this->getInstance()->create($data);

        return $this->getInstance()->find($entry->id);
    }

    /**
     * @param integer $id   ID of updating model.
     * @param array   $data Array representation of updating model.
     * @return Model
     */
    public function update(int $id, array $data): Model
    {
        $this->getInstance()->findOrFail($id)->update($data);

        return $this->getInstance()->find($id);
    }

    /**
     * @param integer $id ID of finding model.
     * @return Model
     *
     * @throws ModelNotFoundException
     */
    public function find(int $id): Model
    {
        return $this->getInstance()->findOrFail($id);
    }

    /**
     * @param array $whereClauses Array of parameters to filter result.
     * @return Model
     */
    public function findWhere(array $whereClauses): ?Model
    {
        return $this->getInstance()->where($whereClauses)->first();
    }

    /**
     * @param integer $id   ID of deleting model.
     * @param boolean $soft
     * @return boolean
     */
    public function delete(int $id, bool $soft = false): bool
    {
        $model = $this->getInstance()->findOrFail($id);

        if ($soft) {
            return $model->delete();
        }

        return $model->forceDelete();
    }

    /**
     * @return Collection Collection of all retrieved models from the database.
     */
    public function all(): Collection
    {
        return $this->getInstance()->all();
    }
}
