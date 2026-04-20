<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResourceDownloadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    protected function makeResource(array $overrides = []): Resource
    {
        $file = UploadedFile::fake()->create('meal-prep-guide.pdf', 20, 'application/pdf');
        $path = $file->store('resources', 'public');

        return Resource::query()->create(array_merge([
            'title' => 'Meal Prep Guide',
            'slug' => 'meal-prep-guide',
            'description' => 'A downloadable meal prep guide.',
            'file' => $path,
            'cover_image' => null,
            'download_count' => 0,
            'is_active' => true,
        ], $overrides));
    }

    public function test_download_captures_email_increments_counter_and_serves_attachment(): void
    {
        $resource = $this->makeResource();

        $response = $this->post("/resources/{$resource->slug}/download", [
            'email' => 'amaka@example.test',
            'name' => 'Amaka',
        ]);

        $response->assertOk();
        $disposition = (string) $response->headers->get('Content-Disposition');
        $this->assertStringContainsString('attachment', $disposition);
        $this->assertStringContainsString(basename($resource->fresh()->file), $disposition);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'amaka@example.test',
            'name' => 'Amaka',
            'source' => 'resource:meal-prep-guide',
        ]);

        $this->assertSame(1, (int) $resource->fresh()->download_count);
    }

    public function test_download_rejects_missing_email(): void
    {
        $resource = $this->makeResource();

        $response = $this->from('/resources')->post("/resources/{$resource->slug}/download", []);

        $response->assertSessionHasErrors('email');
        $this->assertSame(0, NewsletterSubscriber::count());
        $this->assertSame(0, (int) $resource->fresh()->download_count);
    }

    public function test_download_rejects_filled_honeypot(): void
    {
        $resource = $this->makeResource();

        $response = $this->from('/resources')->post("/resources/{$resource->slug}/download", [
            'email' => 'amaka@example.test',
            'website' => 'http://spam.test',
        ]);

        $response->assertSessionHasErrors('website');
        $this->assertSame(0, NewsletterSubscriber::count());
    }

    public function test_download_404_for_inactive_resource(): void
    {
        $resource = $this->makeResource(['is_active' => false]);

        $response = $this->post("/resources/{$resource->slug}/download", [
            'email' => 'amaka@example.test',
        ]);

        $response->assertNotFound();
    }
}
