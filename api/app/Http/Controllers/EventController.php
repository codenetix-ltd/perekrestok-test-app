<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventPaginateRequest;
use App\Http\Requests\EventUpdatePartiallyRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventResourceCollection;
use App\Services\EventServiceInterface;
use Illuminate\Http\Response;

class EventController extends Controller
{
    /**
     * @param integer               $id
     * @param EventServiceInterface $eventService
     * @return EventResource
     */
    public function get(int $id, EventServiceInterface $eventService)
    {
        $event = $eventService->find($id);

        return (new EventResource($event));
    }

    /**
     * @param integer                     $id
     * @param EventUpdatePartiallyRequest $request
     * @param EventServiceInterface       $eventService
     * @return EventResource
     */
    public function updatePartially(int $id, EventUpdatePartiallyRequest $request, EventServiceInterface $eventService)
    {
        $event = $eventService->update($id, $request->filtered());

        return (new EventResource($event));
    }

    /**
     * @param EventPaginateRequest  $request
     * @param EventServiceInterface $eventService
     * @return EventResourceCollection
     */
    public function paginate(EventPaginateRequest $request, EventServiceInterface $eventService)
    {
        $events = $eventService->paginate($request->queryParamsObject());

        return new EventResourceCollection($events);
    }

    /**
     * @param integer               $id
     * @param EventServiceInterface $eventService
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id, EventServiceInterface $eventService)
    {
        $eventService->delete($id, true);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
