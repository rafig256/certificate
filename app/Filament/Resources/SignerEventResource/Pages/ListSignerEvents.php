<?php

namespace App\Filament\Resources\SignerEventResource\Pages;

use App\Filament\Resources\SignerEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSignerEvents extends ListRecords
{
    protected static string $resource = SignerEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
