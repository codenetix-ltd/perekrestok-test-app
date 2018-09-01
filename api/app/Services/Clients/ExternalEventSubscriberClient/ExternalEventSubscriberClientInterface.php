<?php namespace App\Services\Clients\ExternalEventSubscriberClient;

use Illuminate\Support\Collection;

interface ExternalEventSubscriberClientInterface
{
    /**
     * @param Collection $collection
     * @return void
     */
    public function push(Collection $collection): void;
}
