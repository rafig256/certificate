<?php

namespace App\Filament\Resources\SignerEventResource\Pages;

use App\Filament\Resources\SignerEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSignerEvent extends EditRecord
{
    protected static string $resource = SignerEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
