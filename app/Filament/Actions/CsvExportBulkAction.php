<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExportBulkAction
{
    /**
     * @param  array<string, string>  $columns  Map of attribute => header label.
     */
    public static function make(string $filename, array $columns): BulkAction
    {
        return BulkAction::make('export_csv')
            ->label('Export CSV')
            ->icon('heroicon-o-arrow-down-tray')
            ->deselectRecordsAfterCompletion()
            ->action(function (Collection $records) use ($filename, $columns): StreamedResponse {
                return response()->streamDownload(function () use ($records, $columns) {
                    $out = fopen('php://output', 'w');
                    fputcsv($out, array_values($columns));
                    foreach ($records as $record) {
                        $row = [];
                        foreach (array_keys($columns) as $attr) {
                            $value = data_get($record, $attr);
                            if ($value instanceof \DateTimeInterface) {
                                $value = $value->format('Y-m-d H:i:s');
                            }
                            $row[] = is_array($value) ? json_encode($value) : (string) $value;
                        }
                        fputcsv($out, $row);
                    }
                    fclose($out);
                }, $filename);
            });
    }
}
