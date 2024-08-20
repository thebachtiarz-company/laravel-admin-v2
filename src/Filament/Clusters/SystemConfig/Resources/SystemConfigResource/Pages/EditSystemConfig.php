<?php

namespace TheBachtiarz\Admin\Filament\Clusters\SystemConfig\Resources\SystemConfigResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\IconPosition;
use Illuminate\Database\Eloquent\Model;
use TheBachtiarz\Admin\Filament\Clusters\SystemConfig\Resources\SystemConfigResource;
use TheBachtiarz\Base\DTOs\Services\ResponseDataDTO;
use TheBachtiarz\Config\Enums\Services\ConfigIsEncryptEnum;
use TheBachtiarz\Config\Interfaces\Models\ConfigInterface;
use TheBachtiarz\Config\Interfaces\Repositories\ConfigRepositoryInterface;
use TheBachtiarz\Config\Interfaces\Services\ConfigServiceInterface;

class EditSystemConfig extends EditRecord
{
    protected static string $resource = SystemConfigResource::class;

    protected ConfigRepositoryInterface $configRepository;

    protected ConfigServiceInterface $configService;

    protected ResponseDataDTO $processResponse;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->configRepository = app(ConfigRepositoryInterface::class);
        $this->configService = app(ConfigServiceInterface::class);
        $this->processResponse = new ResponseDataDTO();
    }

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
        $entity = $this->configRepository->getByPath(path: $data[ConfigInterface::ATTRIBUTE_PATH]);

        return [
            ConfigInterface::ATTRIBUTE_PATH => $entity->getPath(),
            ConfigInterface::ATTRIBUTE_VALUE => $entity->getValue(),
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
                ConfigInterface::ATTRIBUTE_PATH => ['required', 'string'],
                ConfigInterface::ATTRIBUTE_VALUE => ['required', 'string'],
                ConfigInterface::ATTRIBUTE_IS_ENCRYPT => ['boolean'],
            ],
        );

        if ($validate->errors()->count()) {
            Notification::make()
                ->danger()
                ->title('Some field are incorrect!')
                ->body(json_encode($validate->errors()->getMessages()))
                ->send();
        }

        return $validate->validated();
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $this->processResponse = $this->configService->createOrUpdate(
            pathName: $data[ConfigInterface::ATTRIBUTE_PATH],
            value: $data[ConfigInterface::ATTRIBUTE_VALUE],
            isEncrypt: ConfigIsEncryptEnum::tryFrom(@$data[ConfigInterface::ATTRIBUTE_IS_ENCRYPT]) ?? ConfigIsEncryptEnum::FALSE,
        );

        if (!$this->processResponse->condition->value) {
            return $record;
        }

        $entity = $this->configRepository->getByPath($this->processResponse->data[ConfigInterface::ATTRIBUTE_PATH]);

        assert($entity instanceof Model);

        return $entity;
    }

    protected function afterSave(): void
    {
        // Runs after the form fields are saved to the database.
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->{$this->processResponse->condition->value ? 'success' : 'danger'}()
            ->title($this->processResponse->condition->value ? 'Changes has been saved!' : 'Failed to update config!')
            ->body($this->processResponse->condition->value ? null : $this->processResponse->message)
            ->send();
    }
}
