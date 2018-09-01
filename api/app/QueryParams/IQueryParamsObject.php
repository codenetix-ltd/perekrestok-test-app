<?php

namespace App\QueryParams;

/**
 * Created by Andrew Sparrow <andrew.sprw@gmail.com>
 */
interface IQueryParamsObject
{

    /**
     * @return array
     */
    public function getAllowedFieldsToFilter(): array;

    /**
     * @return array
     */
    public function getAllowedIncludes(): array;

    /**
     * @return array
     */
    public function getAllowedFieldsToSort(): array;

    /**
     * @param array $allowedFieldToFilter
     * @return IQueryParamsObject
     */
    public function setAllowedFieldsToFilter(array $allowedFieldToFilter): IQueryParamsObject;

    /**
     * @param array $allowedIncludes
     * @return IQueryParamsObject
     */
    public function setAllowedIncludes(array $allowedIncludes): IQueryParamsObject;

    /**
     * @param array $allowedFieldToSort
     * @return IQueryParamsObject
     */
    public function setAllowedFieldsToSort(array $allowedFieldToSort): IQueryParamsObject;

    /**
     * @return array
     */
    public function getFiltersData(): array;

    /**
     * @return array
     */
    public function getSortsData(): array;

    /**
     * @return array
     */
    public function getIncludeData(): array;

    /**
     * @return array
     */
    public function getPaginationData(): array;
}
