<?php

namespace App\Services\Clients\EventExternalServiceClient;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Collection;

class EventExternalServiceClientMock implements EventExternalServiceClientInterface
{
    /**
     * @var string
     */
    private $eventLineRegex = '/^([0-9]+) ([0-9]{2}\.[0-9]{2}\.[0-9]{4} [0-9]{2}\.[0-9]{2}) (.+)$/i';

    /**
     * @param Carbon $dateTime
     * @return Collection
     */
    public function requestFromDateTime(Carbon $dateTime): Collection
    {
        $events = collect();

        $this->readFileLineByLine(config('mock.filePathWithFakeEvents'), function ($line) use ($events) {
            if ($event = $this->parseLine($line)) {
                $events->push($event);
            }
        });


        return $events->filter(function ($currentEvent) use ($dateTime) {
            /**
             * @var ExternalEvent $currentEvent
             */
            return $currentEvent->getDateTime()->greaterThan($dateTime);
        });
    }

    /**
     * @param string  $fileName
     * @param Closure $onNewLine
     * @return void
     */
    private function readFileLineByLine(string $fileName, Closure $onNewLine): void
    {
        if (file_exists($fileName) && is_readable($fileName)) {
            $fileResource = fopen($fileName, "r");
            if ($fileResource) {
                while (($line = fgets($fileResource)) !== false) {
                    $onNewLine($line);
                }
                fclose($fileResource);
            }
        }
    }

    /**
     * @param string $line
     * @return ExternalEvent|null
     */
    private function parseLine(string $line)
    {
        if (!preg_match($this->eventLineRegex, $line, $matches)) {
            return null;
        }

        return (new RawToObjectExternalEventTransformer())->transform([
            'userId' => $matches[1],
            'dateTime' => Carbon::createFromFormat('d.m.Y H.i', $matches[2]),
            'message' => $matches[3],
        ]);
    }
}
