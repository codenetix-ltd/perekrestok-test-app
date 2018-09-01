<?php

namespace App\QueryParams;

use Symfony\Component\HttpFoundation\Request;

/**
 * Created by Andrew Sparrow <andrew.sprw@gmail.com>
 */
abstract class QueryParamsObject implements IQueryParamsObject
{

    /**
     * @var array
     */
    protected $filterData;

    /**
     * @var array
     */
    protected $sortData;

    /**
     * @var array
     */
    protected $includeData;

    /**
     * @var array
     */
    protected $paginationData;

    /**
     * @var array
     */
    protected $allowedFieldsToFilter = [];

    /**
     * @var array
     */
    protected $allowedIncludes = [];

    /**
     * @var array
     */
    protected $allowedFieldsToSort = [];

    /**
     * QueryParamsObject constructor.
     * @param array $filterData
     * @param array $sortData
     * @param array $includeData
     * @param array $paginationData
     */
    public function __construct(array $filterData, array $sortData, array $includeData, array $paginationData)
    {
        $this->filterData = $filterData;
        $this->sortData = $sortData;
        $this->includeData = $includeData;
        $this->paginationData = $paginationData;
    }

    /**
     * @param Request $request
     * @return IQueryParamsObject
     */
    public static function makeFromRequest(Request $request): IQueryParamsObject
    {
        $rawFilterData = $request->query('filter', []);

        $filterData = [];
        foreach ($rawFilterData as $filterField => $fieldValue) {
            $parts = explode(',', $fieldValue);
            if (count($parts) > 1) {
                $filterData[$filterField] = $parts;
            } else {
                $filterData[$filterField] = $fieldValue;
            }
        }

        $sortData = $request->query('sort', []);
        $includeData = $request->query('include', []);
        $paginationData = $request->query('pagination', []);

        return new static($filterData, $sortData, $includeData, $paginationData);
    }

    /**
     * @return array
     */
    public function getAllowedFieldsToFilter(): array
    {
        return $this->allowedFieldsToFilter;
    }

    /**
     * @return array
     */
    public function getAllowedIncludes(): array
    {
        return $this->allowedIncludes;
    }

    /**
     * @return array
     */
    public function getAllowedFieldsToSort(): array
    {
        return $this->allowedFieldsToFilter;
    }

    /**
     * @return array
     */
    public function getFiltersData(): array
    {
        return array_intersect_key($this->filterData, array_flip($this->getAllowedFieldsToFilter()));
    }

    /**
     * @return array
     */
    public function getSortsData(): array
    {
        return array_intersect_key($this->sortData, array_flip($this->getAllowedFieldsToSort()));
    }

    /**
     * @return array
     */
    public function getIncludeData(): array
    {
        return array_intersect_assoc($this->includeData, $this->getAllowedIncludes());
    }

    /**
     * @return array
     */
    public function getPaginationData(): array
    {
        return [
            'page' => (int)array_get($this->paginationData, 'page', 1),
            'perPage' => (int)min(array_get($this->paginationData, 'perPage', config('pagination.defaultPerPage')), config('pagination.maxItemsPerPage'))
        ];
    }

    /**
     * @param array $allowedFieldsToFilter
     * @return IQueryParamsObject
     */
    public function setAllowedFieldsToFilter(array $allowedFieldsToFilter): IQueryParamsObject
    {
        $this->allowedFieldsToFilter = $allowedFieldsToFilter;

        return $this;
    }

    /**
     * @param array $allowedIncludes
     * @return IQueryParamsObject
     */
    public function setAllowedIncludes(array $allowedIncludes): IQueryParamsObject
    {
        $this->allowedIncludes = $allowedIncludes;

        return $this;
    }

    /**
     * @param array $allowedFieldsToSort
     * @return IQueryParamsObject
     */
    public function setAllowedFieldsToSort(array $allowedFieldsToSort): IQueryParamsObject
    {
        $this->allowedFieldsToSort = $allowedFieldsToSort;

        return $this;
    }
}
