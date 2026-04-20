<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourceDownloadRequest;
use App\Models\NewsletterSubscriber;
use App\Models\Resource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResourceController extends Controller
{
    public function index(): InertiaResponse
    {
        $resources = Resource::query()
            ->where('is_active', true)
            ->orderByDesc('id')
            ->get()
            ->map(fn (Resource $r) => [
                'id' => $r->id,
                'title' => $r->title,
                'slug' => $r->slug,
                'description' => $r->description,
                'cover_image' => $r->cover_image,
                'download_count' => (int) $r->download_count,
            ])
            ->all();

        return Inertia::render('Resources/Index', [
            'resources' => $resources,
        ]);
    }

    public function download(ResourceDownloadRequest $request, string $slug): BinaryFileResponse|StreamedResponse|Response
    {
        $resource = Resource::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $data = $request->validated();

        DB::transaction(function () use ($data, $resource) {
            NewsletterSubscriber::query()->updateOrCreate(
                ['email' => strtolower(trim($data['email']))],
                [
                    'name' => $data['name'] ?? null,
                    'source' => 'resource:'.$resource->slug,
                    'subscribed_at' => now(),
                    'unsubscribed_at' => null,
                ],
            );

            $resource->increment('download_count');
        });

        $disk = Storage::disk('public');
        if (! $disk->exists($resource->file)) {
            abort(404, 'File not found.');
        }

        $filename = basename($resource->file);

        return $disk->download($resource->file, $filename, [
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
