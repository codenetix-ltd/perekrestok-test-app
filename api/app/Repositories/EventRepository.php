<?php

namespace App\Repositories;

use App\Event;
use App\QueryObject\EventListQueryObject;
use App\QueryParams\IQueryParamsObject;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EventRepository implements EventRepositoryInterface
{
    use CRUDRepositoryTrait;

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getInstance()
    {
        return new Event();
    }

    /**
     * @param IQueryParamsObject $queryParamsObject
     * @return LengthAwarePaginator
     */
    public function paginateList(IQueryParamsObject $queryParamsObject): LengthAwarePaginator
    {
        $paginationParameters = $queryParamsObject->getPaginationData();

        return (new EventListQueryObject($this->getInstance()->newQuery()))
            ->applyQueryParams($queryParamsObject)
            ->paginate(
                $paginationParameters['perPage'],
                ['*'],
                'page',
                $paginationParameters['page']
            );
    }

    /**
     * @return Carbon|null
     */
    public function maxFiredAtDateTime(): ?Carbon
    {
        $date = $this->getInstance()->max('fired_at');

        return $date ? new Carbon($date) : null;
    }

    /**
     * @return Collection
     */
    public function allNotSent(): Collection
    {
        return $this->getInstance()->where('is_sent', 0)->get();
    }
}
