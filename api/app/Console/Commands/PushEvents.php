<?php

namespace App\Console\Commands;

use App\Repositories\EventRepositoryInterface;
use App\Services\Clients\ExternalEventSubscriberClient\ExternalEventSubscriberClientInterface;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\Container;
use Exception;

class PushEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pk:push-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pushes the events from the local database to th remote source (like Bitrix)';
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
         * @var ExternalEventSubscriberClientInterface $eventServiceClient
         */
        $externalEventSubscriber = $this->container->make(ExternalEventSubscriberClientInterface::class);

        /**
         * EventRepositoryInterface $eventRepository
         */
        $eventRepository = $this->container->make(EventRepositoryInterface::class);

        $newEvents = $eventRepository->allNotSent();

        try {
            // Try to push into external system (wrapper under API)
            $externalEventSubscriber->push($newEvents);
        } catch (Exception $e) {
            $this->warn($e->getMessage());
        }

        $this->line($newEvents->count().' new events has been push into external system (Bitrix?)');
    }
}
