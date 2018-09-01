<?php

namespace App\QueryObject;

use App\QueryParams\IQueryParamsObject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class AQueryObject
{
    /**
     * @var Builder
     */
    private $baseQuery;

    /**
     * AQueryObject constructor.
     * @param Builder $baseQuery
     */
    public function __construct(Builder $baseQuery)
    {
        $this->baseQuery = $baseQuery;
    }

    /**
     * @return Builder
     */
    protected function getQuery(): Builder
    {
        return $this->baseQuery;
    }

    /**
     * @return array
     */
    protected function map()
    {
        return [];
    }

    /**
     * @param array         $path
     * @param Model|Builder $model
     * @return Model|Builder
     */
    private function applyJoins(array $path, $model)
    {
        array_pop($path);

        $scope = [];
        while ($relationName = array_shift($path)) {
            array_push($scope, $relationName);
            $model = $this->applyJoin($model, implode('.', $scope));
        }

        return $model;
    }

    /**
     * @param IQueryParamsObject $queryParamsObject
     * @return Model|Builder
     */
    public function applyQueryParams(IQueryParamsObject $queryParamsObject)
    {
        $model = $this->getQuery()->select($this->getQuery()->getModel()->getTable() . '.*');

        foreach ($queryParamsObject->getSortsData() as $field => $direction) {
            $map = $this->map();
            $field = isset($map[$field]) ? $map[$field] : $field;

            $parts = explode('.', $field);

            if (count($parts) > 1) {
                $model = $this->applyJoins($parts, $model);
                $field = implode('.', [$parts[count($parts) - 2], $parts[count($parts) - 1]]);
            }

            $model = $model->orderBy($field, $direction);
        }

        foreach ($queryParamsObject->getFiltersData() as $field => $value) {
            $map = $this->map();
            $scope = isset($map[$field]) ? $map[$field] : $field;

            $scopeParts = explode('.', $scope);
            if (count($scopeParts) > 1) {
                $model = $this->applyJoins($scopeParts, $model);
                $model = $this->applyWhere($model, implode('.', [$scopeParts[count($scopeParts) - 2], $scopeParts[count($scopeParts) - 1]]), $value, $scope);
            } else {
                $model = $this->applyWhere($model, $scope, $value, $scope);
            }
        }

        return $model;
    }

    /**
     * @param Model|Builder $model
     * @param string        $field
     * @param string|array  $value
     * @param string        $scope
     * @return Model|Builder
     */
    protected function applyWhere($model, string $field, $value, string $scope)
    {
        if (!is_numeric($value)) {
            return $model->where($field, 'LIKE', '%' . $value . '%');
        } else {
            return $model->where($field, $value);
        }
    }

    /**
     * @param Model|Builder $model
     * @param string        $scope
     * @return Model|Builder
     */
    protected function applyJoin($model, string $scope)
    {
        return $model;
    }
}
