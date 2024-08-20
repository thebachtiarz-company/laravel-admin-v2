<?php

namespace TheBachtiarz\Admin\Filament\Clusters\SystemConfig\Resources\SystemConfigResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconPosition;
use TheBachtiarz\Admin\Filament\Clusters\SystemConfig\Resources\SystemConfigResource;

class ListSystemConfigs extends ListRecords
{
    protected static string $resource = SystemConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add New Config')->icon('heroicon-c-plus')->iconPosition(IconPosition::Before),
        ];
    }
}
