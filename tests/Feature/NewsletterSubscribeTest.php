<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterSubscribeTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscribe_creates_subscriber_with_default_footer_source(): void
    {
        $response = $this->from('/')->post('/newsletter/subscribe', [
            'email' => 'amaka@example.test',
            'name' => 'Amaka',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'amaka@example.test',
            'name' => 'Amaka',
            'source' => 'footer',
        ]);
    }

    public function test_subscribe_lowercases_email_and_is_idempotent(): void
    {
        $this->post('/newsletter/subscribe', ['email' => 'amaka@example.test']);
        $this->post('/newsletter/subscribe', ['email' => 'amaka@example.test']);

        $this->assertSame(1, NewsletterSubscriber::count());
        $this->assertDatabaseHas('newsletter_subscribers', ['email' => 'amaka@example.test']);
    }

    public function test_subscribe_rejects_filled_honeypot(): void
    {
        $response = $this->from('/')->post('/newsletter/subscribe', [
            'email' => 'amaka@example.test',
            'website' => 'http://spam.test',
        ]);

        $response->assertSessionHasErrors('website');
        $this->assertSame(0, NewsletterSubscriber::count());
    }

    public function test_subscribe_rejects_invalid_email(): void
    {
        $response = $this->from('/')->post('/newsletter/subscribe', [
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertSame(0, NewsletterSubscriber::count());
    }
}
