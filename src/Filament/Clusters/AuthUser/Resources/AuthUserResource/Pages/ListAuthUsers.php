<?php

namespace TheBachtiarz\Admin\Filament\Clusters\AuthUser\Resources\AuthUserResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconPosition;
use TheBachtiarz\Admin\Filament\Clusters\AuthUser\Resources\AuthUserResource;

class ListAuthUsers extends ListRecords
{
    protected static string $resource = AuthUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add New User')->icon('heroicon-c-plus')->iconPosition(IconPosition::Before),
        ];
    }
}
