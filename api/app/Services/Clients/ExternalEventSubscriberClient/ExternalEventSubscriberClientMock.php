<?php
namespace App\Services\Clients\ExternalEventSubscriberClient;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class ExternalEventSubscriberClientMock implements ExternalEventSubscriberClientInterface
{
    /**
     * @param Collection $collection
     * @return void
     */
    public function push(Collection $collection): void
    {
        File::append(config('mock.filePathWithEventSubscriberLog'), '['.date('d-m-Y H:i').'] '.print_r($collection->toArray(), true));
    }
}
