<?php

namespace TheBachtiarz\Admin\Filament\Clusters\SystemConfig\Resources\SystemConfigResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use TheBachtiarz\Admin\Filament\Clusters\SystemConfig\Resources\SystemConfigResource;
use TheBachtiarz\Config\Enums\Services\ConfigIsEncryptEnum;
use TheBachtiarz\Config\Http\Requests\Rules\ConfigPathRule;
use TheBachtiarz\Config\Http\Requests\Rules\ConfigValueRule;
use TheBachtiarz\Config\Interfaces\Models\ConfigInterface;
use TheBachtiarz\Config\Interfaces\Repositories\ConfigRepositoryInterface;
use TheBachtiarz\Config\Interfaces\Services\ConfigServiceInterface;

class EditSystemConfig extends EditRecord
{
    protected static string $resource = SystemConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Delete')->icon('heroicon-s-trash')->iconPosition(IconPosition::Before),
        ];
    }

    protected function beforeFill(): void
    {
        // Runs before the form fields are populated from the database.
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $entity = app(ConfigRepositoryInterface::class)->getByPath(path: $data[ConfigInterface::ATTRIBUTE_PATH]);

        return [
            ConfigInterface::ATTRIBUTE_PATH => $entity->getPath(),
            ConfigInterface::ATTRIBUTE_VALUE => $entity->{ConfigInterface::VALUE_FORMATTED},
            ConfigInterface::ATTRIBUTE_IS_ENCRYPT => $entity->getIsEncrypt(),
        ];
    }

    protected function afterFill(): void
    {
        // Runs after the form fields are populated from the database.
    }

    protected function beforeValidate(): void
    {
        // Runs before the form fields are validated when the form is saved.
    }

    protected function afterValidate(): void
    {
        // Runs after the form fields are validated when the form is saved.
    }

    protected function beforeSave(): void
    {
        // Runs before the form fields are saved to the database.
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $validate = validator(
            data: $data,
            rules: [
                ConfigInterface::ATTRIBUTE_PATH => ConfigPathRule::rules()[ConfigPathRule::PATH],
                ConfigInterface::ATTRIBUTE_VALUE => ConfigValueRule::rules()[ConfigValueRule::VALUE],
                ConfigInterface::ATTRIBUTE_IS_ENCRYPT => ['boolean'],
            ],
        );

        if ($validate->errors()->count()) {
            Notification::make()
                ->danger()
                ->title('Some field are incorrect!')
                ->body(json_encode($validate->errors()->getMessages()))
                ->send();

            throw new Halt();
        }

        return $validate->validated();
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $process = app(ConfigServiceInterface::class)->createOrUpdate(
            pathName: $data[ConfigInterface::ATTRIBUTE_PATH],
            value: $data[ConfigInterface::ATTRIBUTE_VALUE],
            isEncrypt: ConfigIsEncryptEnum::tryFrom(@$data[ConfigInterface::ATTRIBUTE_IS_ENCRYPT]) ?? ConfigIsEncryptEnum::FALSE,
        );

        if (!$process->condition->toBoolean()) {
            Notification::make()
                ->warning()
                ->title($process->message)
                ->send();

            throw new Halt();
        }

        return $process->model;
    }

    protected function afterSave(): void
    {
        // Runs after the form fields are saved to the database.
    }
}
