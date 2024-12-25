<?php

namespace TheBachtiarz\Admin\Filament\Clusters\SystemConfig\Resources\SystemConfigResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use TheBachtiarz\Admin\Filament\Clusters\SystemConfig\Resources\SystemConfigResource;
use TheBachtiarz\Config\Enums\Services\ConfigIsEncryptEnum;
use TheBachtiarz\Config\Http\Requests\Rules\ConfigPathRule;
use TheBachtiarz\Config\Http\Requests\Rules\ConfigValueRule;
use TheBachtiarz\Config\Interfaces\Models\ConfigInterface;
use TheBachtiarz\Config\Interfaces\Services\ConfigServiceInterface;

class CreateSystemConfig extends CreateRecord
{
    protected static string $resource = SystemConfigResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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

    protected function handleRecordCreation(array $data): Model
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
}
