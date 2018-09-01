<?php

namespace Tests\Feature;

use App\Event;
use App\Http\Resources\EventResource;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testGetEvent()
    {

        $event = factory(Event::class)->create();

        $this
            ->json('GET', 'api/events/' . $event->id)
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(['data' => (new EventResource($event))->jsonSerialize()]);
    }

    /**
     * @return void
     */
    public function testListEvents()
    {
        $events = factory(Event::class, 20)->create();

        $response = $this
            ->json('GET', 'api/events')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(config('pagination.defaultPerPage'), 'data');

        // Check every response item for correct structure
        collect($response->decodeResponseJson()['data'])->every(function ($item, $i) use ($events) {
            $this->assertEquals($item, (new EventResource($events[$i]))->jsonSerialize());
        });
    }

    /**
     * @return void
     */
    public function testListEventsFilterByUser()
    {
        $events = factory(Event::class, 50)->create();

        $this
            ->json('GET', 'api/events?filter[user]=123')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(0, 'data');

        $this
            ->json('GET', 'api/events?filter[user]=' . $events[0]->user->name)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data');
    }

    /**
     * @return void
     */
    public function testListEventsFilterById()
    {
        $events = factory(Event::class, 10)->create();

        $this
            ->json('GET', 'api/events?filter[id]='.$events[0]->id)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'id' => $events[0]->id
            ]);
    }

    /**
     * @return void
     */
    public function testListEventsFilterByPeriod()
    {
        $faker = Factory::create();

        $events = factory(Event::class, 50)->create([
            'fired_at' => $faker->dateTime('1980-08-10')
        ]);

        $this
            ->json('GET', 'api/events?pagination[perPage]=50&filter[from]=1980-08-10')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(0, 'data');

        $this
            ->json('GET', 'api/events?pagination[perPage]=50&filter[to]=1980-08-10')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($events->count(), 'data');
    }

    /**
     * @return void
     */
    public function testListEventsFilterByPeriodComplex()
    {
        $events[] = factory(Event::class, 1)->create([
            'fired_at' => Carbon::parse('2018-06-15 12:00:00')
        ]);

        $events[] = factory(Event::class, 1)->create([
            'fired_at' => Carbon::parse('2018-07-15 13:00:00')
        ]);

        $events[] = factory(Event::class, 1)->create([
            'fired_at' => Carbon::parse('2018-08-15 14:00:00')
        ]);

        $this
            ->json('GET', 'api/events?pagination[perPage]=50&filter[from]=2018-07-10')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(2, 'data');

        $this
            ->json('GET', 'api/events?pagination[perPage]=50&filter[from]=2017-07-01')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3, 'data');

        $this
            ->json('GET', 'api/events?pagination[perPage]=50&filter[from]=2018-10-01')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(0, 'data');

        $this
            ->json('GET', 'api/events?pagination[perPage]=50&filter[from]=2018-07-10&filter[to]=2018-07-20')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data');

        // Check wrong value result
        $this
            ->json('GET', 'api/events?pagination[perPage]=50&filter[from]=abc')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3, 'data');
    }

    /**
     * @return void
     */
    public function testListEventsOnlyHidden()
    {
        $events = factory(Event::class, 50)->create();

        $hiddenTotal = $events->where('is_hidden', true)->count();

        $this
            ->json('GET', 'api/events?filter[hidden]=1&pagination[page]=1&pagination[perPage]=50')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($hiddenTotal, 'data');
    }

    /**
     * @return void
     */
    public function testUpdateEventStatusAsViewed()
    {
        $event = factory(Event::class)->create(['is_viewed' => false]);

        $this
            ->json('PATCH', 'api/events/' . $event->id, [
                'is_viewed' => true
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'is_viewed' => true
            ]);
    }

    /**
     * @return void
     */
    public function testUpdateEventStatusAsHidden()
    {
        $event = factory(Event::class)->create();

        $this
            ->json('PATCH', 'api/events/' . $event->id, [
                'is_hidden' => true
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'is_hidden' => true
            ]);

        $this
            ->json('PATCH', 'api/events/' . $event->id, [
                'is_hidden' => false
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'is_hidden' => false
            ]);
    }

    /**
     * @return void
     */
    public function testSoftDeleteEvent()
    {
        $event = factory(Event::class)->create();

        $this
            ->json('DELETE', 'api/events/' . $event->id)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertCount(0, Event::all());

        // Check soft delete feature influence
        $this->assertCount(1, Event::withTrashed()->get());
    }
}
