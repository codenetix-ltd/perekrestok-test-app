<?php

namespace App\Services;

use App\Enums\EventType;
use App\Repositories\EventRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\Clients\EventExternalServiceClient\ExternalEvent;
use App\Services\Clients\UserExternalServiceClient\UserExternalServiceClientInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EventService implements EventServiceInterface
{
    use CRUDServiceTrait;
    /**
     * @var UserExternalServiceClientInterface
     */
    private $userExternalClient;
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * EventService constructor.
     * @param EventRepositoryInterface           $repository
     * @param UserExternalServiceClientInterface $userExternalClient
     * @param UserRepositoryInterface            $userRepository
     */
    public function __construct(EventRepositoryInterface $repository, UserExternalServiceClientInterface $userExternalClient, UserRepositoryInterface $userRepository)
    {
        $this->setRepository($repository);
        $this->userExternalClient = $userExternalClient;
        $this->userRepository = $userRepository;
    }

    /**
     * @param ExternalEvent $externalEvent
     * @return Model
     */
    public function createFromExternalEvent(ExternalEvent $externalEvent): Model
    {
        $externalUser = $this->userExternalClient->getUserById($externalEvent->getUserId());
        $localUser = $this->userRepository->findByExternalId($externalUser->getId());

        if (!$localUser) {
            $localUser = $this->userRepository->create([
                'external_user_id' => $externalUser->getId(),
                'email' => $externalUser->getEmail(),
                'name' => $externalUser->getName(),
                'password' => Hash::make(str_random(16)),
            ]);
        }

        return $this->repository->create([
            'user_id' => $localUser->id,
            'type' => EventType::DEFAULT,
            'message' => $externalEvent->getMessage(),
            'link' => 'TODO',
            'fired_at' => $externalEvent->getDateTime()
        ]);
    }
}
