<?php
namespace App\Services;

use App\Event;
use App\Services\Clients\EventExternalServiceClient\ExternalEvent;
use Illuminate\Database\Eloquent\Model;

interface EventServiceInterface extends CRUDServiceInterface
{
    /**
     * @param ExternalEvent $externalEvent
     * @return Event
     */
    public function createFromExternalEvent(ExternalEvent $externalEvent): Model;
}
