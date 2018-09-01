<?php namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface EventRepositoryInterface extends CRUDRepositoryInterface
{
    /**
     * @return Carbon|null
     */
    public function maxFiredAtDateTime(): ?Carbon;

    /**
     * @return Collection
     */
    public function allNotSent(): Collection;
}
