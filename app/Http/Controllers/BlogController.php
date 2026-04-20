<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(Request $request): Response
    {
        $query = BlogPost::query()
            ->with('author:id,name')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        if ($category = $request->query('category')) {
            $query->where('category', $category);
        }

        $featured = (clone $query)->orderByDesc('published_at')->first();

        $posts = $query
            ->when($featured, fn ($q) => $q->where('id', '!=', $featured->id))
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = BlogPost::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->filter()
            ->values()
            ->all();

        return Inertia::render('Blog/Index', [
            'featured' => $featured ? $this->transform($featured) : null,
            'posts' => $posts->through(fn ($p) => $this->transform($p)),
            'categories' => $categories,
            'filters' => [
                'q' => $search ?: null,
                'category' => $category ?: null,
            ],
        ]);
    }

    public function show(string $slug): Response
    {
        $post = BlogPost::with('author:id,name')
            ->where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();

        $post->increment('views');

        $related = BlogPost::with('author:id,name')
            ->where('category', $post->category)
            ->where('id', '!=', $post->id)
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return Inertia::render('Blog/Show', [
            'post' => [
                ...$this->transform($post),
                'body' => $post->body,
                'meta_title' => $post->meta_title,
                'meta_description' => $post->meta_description,
                'tags' => $post->tags ?? [],
                'views' => (int) $post->views,
            ],
            'related' => $related->map(fn ($p) => $this->transform($p))->all(),
        ]);
    }

    protected function transform(BlogPost $post): array
    {
        $readTime = max(1, (int) ceil(str_word_count((string) strip_tags($post->body)) / 200));

        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'cover_image' => $post->cover_image,
            'category' => $post->category,
            'published_at' => $post->published_at?->toDateString(),
            'read_time' => $readTime,
            'author' => $post->author ? [
                'id' => $post->author->id,
                'name' => $post->author->name,
            ] : null,
        ];
    }
}
