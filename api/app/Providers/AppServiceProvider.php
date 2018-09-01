<?php

namespace App\Providers;

use App\Http\Requests\ABaseAPIRequest;
use App\Repositories\EventRepository;
use App\Repositories\EventRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Services\Clients\ExternalEventSubscriberClient\ExternalEventSubscriberClientInterface;
use App\Services\Clients\ExternalEventSubscriberClient\ExternalEventSubscriberClientMock;
use App\Services\EventService;
use App\Services\EventServiceInterface;
use App\Services\Clients\EventExternalServiceClient\EventExternalServiceClientInterface;
use App\Services\Clients\EventExternalServiceClient\EventExternalServiceClientMock;
use App\Services\Clients\UserExternalServiceClient\UserExternalServiceClientInterface;
use App\Services\Clients\UserExternalServiceClient\UserExternalServiceClientMock;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Services
        $this->app->bind(EventServiceInterface::class, EventService::class);

        // Repositories
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        //Mocks
        $this->app->bind(UserExternalServiceClientInterface::class, UserExternalServiceClientMock::class);
        $this->app->bind(EventExternalServiceClientInterface::class, EventExternalServiceClientMock::class);
        $this->app->bind(ExternalEventSubscriberClientInterface::class, ExternalEventSubscriberClientMock::class);

        $this->app->resolving(
            ABaseAPIRequest::class,
            function ($request, $app) {
                $request = ABaseAPIRequest::createFrom($app['request'], $request);
                $request->setContainer($app);
            }
        );
    }
}
