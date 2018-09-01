<?php

namespace App\Console\Commands;

use App\Repositories\EventRepositoryInterface;
use App\Services\Clients\EventExternalServiceClient\EventExternalServiceClientInterface;
use App\Services\EventServiceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\Container;

class PullEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pk:pull-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collects events from the specified source and puts into local database';
    /**
     * @var Container
     */
    private $container;

    /**
     * Create a new command instance.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
         * @var EventExternalServiceClientInterface $eventServiceClient
         */
        $eventServiceClient = $this->container->make(EventExternalServiceClientInterface::class);

        /**
         * EventRepositoryInterface $eventRepository
         */
        $eventRepository = $this->container->make(EventRepositoryInterface::class);

        /**
         * @var EventServiceInterface $eventService
         */
        $eventService = $this->container->make(EventServiceInterface::class);

        $maxEventFiredAtDateTime = $eventRepository->maxFiredAtDateTime();

        // Seems like database is empty
        if (!$maxEventFiredAtDateTime) {
            // Load events right from this moment. Why not?
            $maxEventFiredAtDateTime = Carbon::now();
        }

        // Get new events from event source
        $newRemoteEvents = $eventServiceClient->requestFromDateTime($maxEventFiredAtDateTime);

        // Save new events into local database
        $newRemoteEvents->each(function ($currentNewEvent) use ($eventService) {
            try {
                $eventService->createFromExternalEvent($currentNewEvent);
            } catch (Exception $e) {
                // Problem with event importing
                // @TODO add detailed handling depended on exception type
                $this->warn($e->getMessage());
            }
        });

        $this->line($newRemoteEvents->count().' new events has been imported');
    }
}
