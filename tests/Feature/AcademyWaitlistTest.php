<?php

namespace Tests\Feature;

use App\Models\AcademyWaitlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademyWaitlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_join_waitlist_creates_row(): void
    {
        $response = $this->from('/academy')->post('/academy/waitlist', [
            'name' => 'Amaka',
            'email' => 'amaka@example.test',
            'phone' => '+2348030000001',
            'interest_level' => 'serious',
            'notes' => 'Ready to cook.',
        ]);

        $response->assertRedirect('/academy');
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('academy_waitlist', [
            'name' => 'Amaka',
            'email' => 'amaka@example.test',
            'phone' => '+2348030000001',
            'interest_level' => 'serious',
        ]);
    }

    public function test_join_waitlist_is_idempotent_by_email(): void
    {
        $this->post('/academy/waitlist', [
            'name' => 'Amaka',
            'email' => 'amaka@example.test',
            'phone' => '+2348030000001',
            'interest_level' => 'curious',
        ]);

        $this->post('/academy/waitlist', [
            'name' => 'Amaka Updated',
            'email' => 'amaka@example.test',
            'phone' => '+2348030000001',
            'interest_level' => 'ready',
        ]);

        $this->assertSame(1, AcademyWaitlist::count());
        $this->assertDatabaseHas('academy_waitlist', [
            'email' => 'amaka@example.test',
            'name' => 'Amaka Updated',
            'interest_level' => 'ready',
        ]);
    }

    public function test_join_waitlist_rejects_invalid_interest_level(): void
    {
        $response = $this->from('/academy')->post('/academy/waitlist', [
            'name' => 'Amaka',
            'email' => 'amaka@example.test',
            'phone' => '+2348030000001',
            'interest_level' => 'expert',
        ]);

        $response->assertSessionHasErrors('interest_level');
        $this->assertSame(0, AcademyWaitlist::count());
    }

    public function test_join_waitlist_rejects_filled_honeypot(): void
    {
        $response = $this->from('/academy')->post('/academy/waitlist', [
            'website' => 'http://spam.test',
            'name' => 'Amaka',
            'email' => 'amaka@example.test',
            'phone' => '+2348030000001',
            'interest_level' => 'curious',
        ]);

        $response->assertSessionHasErrors('website');
        $this->assertSame(0, AcademyWaitlist::count());
    }
}
