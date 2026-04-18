<?php

namespace App\Filament\Resources\AcademyWaitlistResource\Pages;

use App\Filament\Resources\AcademyWaitlistResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAcademyWaitlist extends ViewRecord
{
    protected static string $resource = AcademyWaitlistResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
