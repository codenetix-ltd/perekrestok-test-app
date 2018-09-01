<?php
namespace App\Services\Clients\EventExternalServiceClient;

use Carbon\Carbon;
use Illuminate\Support\Collection;

interface EventExternalServiceClientInterface
{
    /**
     * @param Carbon $dateTime
     * @return Collection
     */
    public function requestFromDateTime(Carbon $dateTime) : Collection;
}
