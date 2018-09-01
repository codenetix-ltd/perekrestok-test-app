<?php

namespace App\Http\Requests;

use App\QueryParams\EmptyQueryParamsObject;
use App\QueryParams\IQueryParamsObject;

class EventPaginateRequest extends ABaseAPIRequest
{
    /**
     * @return boolean
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * @return IQueryParamsObject
     */
    protected function createQueryParamsObject(): IQueryParamsObject
    {
        return EmptyQueryParamsObject::makeFromRequest($this)
            ->setAllowedFieldsToFilter(['hidden', 'user', 'id', 'from', 'to', 'message'])
            ->setAllowedFieldsToSort(['id', 'user', 'message']);
    }
}
