<?php

namespace App\Filament\Resources\AcademyWaitlistResource\Pages;

use App\Filament\Resources\AcademyWaitlistResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAcademyWaitlists extends ListRecords
{
    protected static string $resource = AcademyWaitlistResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
