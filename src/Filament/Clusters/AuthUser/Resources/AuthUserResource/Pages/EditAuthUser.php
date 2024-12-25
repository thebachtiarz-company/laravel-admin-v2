<?php

namespace TheBachtiarz\Admin\Filament\Clusters\AuthUser\Resources\AuthUserResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\IconPosition;
use TheBachtiarz\Admin\Filament\Clusters\AuthUser\Resources\AuthUserResource;
use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;
use TheBachtiarz\OAuth\Interfaces\Repositories\AuthUserRepositoryInterface;

class EditAuthUser extends EditRecord
{
    protected static string $resource = AuthUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Delete')->icon('heroicon-s-trash')->iconPosition(IconPosition::Before),
            Actions\RestoreAction::make()->label('Restore')->icon('heroicon-s-arrow-uturn-left')->iconPosition(IconPosition::Before),
            Actions\ForceDeleteAction::make()->label('Destroy')->icon('heroicon-c-trash')->iconPosition(IconPosition::Before),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $entity = app(AuthUserRepositoryInterface::class)->getByCode(code: $data[AuthUserInterface::ATTRIBUTE_CODE]);

        return [
            AuthUserInterface::ATTRIBUTE_CODE => $entity->getCode(),
            AuthUserInterface::ATTRIBUTE_EMAIL => $entity->{AuthUserInterface::ATTRIBUTE_EMAIL},
            AuthUserInterface::ATTRIBUTE_USERNAME => $entity->{AuthUserInterface::ATTRIBUTE_USERNAME},
        ];
    }
}
