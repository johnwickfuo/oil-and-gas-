<?php

namespace App\Models\Concerns;

use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasResponsiveImages
{
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->format('webp')
            ->fit(Fit::Max, 300, 300)
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->format('webp')
            ->fit(Fit::Max, 800, 800)
            ->nonQueued();

        $this->addMediaConversion('large')
            ->format('webp')
            ->fit(Fit::Max, 1600, 1600)
            ->nonQueued();
    }
}
