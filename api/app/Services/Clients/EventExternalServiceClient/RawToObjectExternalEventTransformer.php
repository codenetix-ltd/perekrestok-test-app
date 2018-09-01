<?php

namespace App\Services\Clients\EventExternalServiceClient;

class RawToObjectExternalEventTransformer
{
    /**
     * @param array $rawData
     * @return ExternalEvent
     */
    public function transform(array $rawData)
    {
        $externalEvent = new ExternalEvent();
        $externalEvent->setUserId($rawData['userId']);
        $externalEvent->setMessage($rawData['message']);
        $externalEvent->setDateTime($rawData['dateTime']);
        return $externalEvent;
    }
}
