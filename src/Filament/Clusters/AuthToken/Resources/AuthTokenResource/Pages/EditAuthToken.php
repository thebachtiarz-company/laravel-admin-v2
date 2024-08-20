<?php

namespace TheBachtiarz\Admin\Filament\Clusters\AuthToken\Resources\AuthTokenResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\IconPosition;
use TheBachtiarz\Admin\Filament\Clusters\AuthToken\Resources\AuthTokenResource;

class EditAuthToken extends EditRecord
{
    protected static string $resource = AuthTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Delete')->icon('heroicon-s-trash')->iconPosition(IconPosition::Before),
        ];
    }
}
