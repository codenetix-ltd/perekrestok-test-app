<?php /** @noinspection ALL */

namespace App\QueryObject;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidDateException;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class EventListQueryObject extends AQueryObject
{

    /**
     * @return array
     */
    protected function map()
    {
        return [
            'user' => 'users.name',
            'hidden' => 'is_hidden',
            'id' => 'events.id',
            'message' => 'events.message'
        ];
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model $model
     * @param string                                                                    $scope
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|Builder
     */
    protected function applyJoin($model, string $scope)
    {
        if ($scope === 'users') {
            return $model->leftJoin('users', 'events.user_id', '=', 'users.id');
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
        if (in_array($field, ['from', 'to'])) {
            // Filter bad formatted dates
            if ($timestamp = strtotime($value)) {
                return $model->where('fired_at', $field == 'from' ? '>=' : '<=', Carbon::createFromTimestamp($timestamp));
            } else {
                return $model;
            }
        }

        return parent::applyWhere($model, $field, $value, $scope);
    }
}
