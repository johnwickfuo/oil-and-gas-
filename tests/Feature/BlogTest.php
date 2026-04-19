<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    protected function makePost(array $overrides = []): BlogPost
    {
        $author = User::factory()->create();

        return BlogPost::query()->create(array_merge([
            'title' => 'Cooking with Palm Oil',
            'slug' => 'cooking-with-palm-oil',
            'excerpt' => 'A short intro to palm oil in Nigerian cooking.',
            'body' => '<p>Palm oil is a staple in Nigerian cooking. '
                .str_repeat('This is a word. ', 300).'</p>',
            'cover_image' => null,
            'author_id' => $author->id,
            'category' => 'Tips',
            'tags' => ['palm-oil', 'nigerian'],
            'published_at' => now()->subDay(),
            'views' => 0,
        ], $overrides));
    }

    public function test_show_increments_views(): void
    {
        $post = $this->makePost();

        $this->get("/blog/{$post->slug}")->assertOk();
        $this->get("/blog/{$post->slug}")->assertOk();

        $this->assertSame(2, (int) $post->fresh()->views);
    }

    public function test_index_lists_published_posts(): void
    {
        $this->makePost(['title' => 'Published', 'slug' => 'published']);
        $this->makePost([
            'title' => 'Draft',
            'slug' => 'draft',
            'published_at' => null,
        ]);

        $response = $this->get('/blog');

        $response->assertOk();
        $response->assertInertia(fn ($p) => $p->component('Blog/Index'));
    }

    public function test_show_404_for_unpublished_post(): void
    {
        $post = $this->makePost(['published_at' => null]);

        $this->get("/blog/{$post->slug}")->assertNotFound();
    }
}
