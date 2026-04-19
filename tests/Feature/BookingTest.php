<?php

namespace Tests\Feature;

use App\Events\BookingCreated;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Service::query()->create([
            'title' => 'Private Chef Dinner',
            'slug' => 'private-chef-dinner',
            'short_description' => 'x',
            'long_description' => '<p>x</p>',
            'base_price' => 250000,
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    public function test_booking_store_computes_total_server_side_and_dispatches_event(): void
    {
        Event::fake([BookingCreated::class]);

        $response = $this->from('/book')->post('/book', [
            'service_slug' => 'private-chef-dinner',
            'event_date' => now()->addWeek()->toDateString(),
            'event_time' => '19:00',
            'guests' => 6,
            'location' => 'gra',
            'addons' => ['dessert_course'],
            'name' => 'Amaka',
            'email' => 'amaka@example.test',
            'phone' => '+2348030000001',
        ]);

        $response->assertSessionHasNoErrors();
        $booking = Booking::first();
        $this->assertNotNull($booking);
        $response->assertRedirect(route('booking.confirmation', $booking->reference));

        // 25000/guest * 6 + 5000 addon + 0 location = 155000, 30% = 46500
        $this->assertEquals(155000, (int) $booking->estimated_total);
        $this->assertEquals(46500, (int) $booking->deposit_amount);

        Event::assertDispatched(BookingCreated::class);
    }

    public function test_booking_rejects_filled_honeypot(): void
    {
        $response = $this->post('/book', [
            'website' => 'http://spam.test',
            'service_slug' => 'private-chef-dinner',
            'event_date' => now()->addWeek()->toDateString(),
            'event_time' => '19:00',
            'guests' => 6,
            'location' => 'gra',
            'name' => 'Amaka',
            'email' => 'amaka@example.test',
            'phone' => '+2348030000001',
        ]);

        $response->assertSessionHasErrors('website');
        $this->assertSame(0, Booking::count());
    }
}
