<?php

namespace TheBachtiarz\Admin\Filament\Clusters\SystemConfig\Resources\SystemConfigResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use TheBachtiarz\Admin\Filament\Clusters\SystemConfig\Resources\SystemConfigResource;
use TheBachtiarz\Base\DTOs\Services\ResponseDataDTO;
use TheBachtiarz\Config\Enums\Services\ConfigIsEncryptEnum;
use TheBachtiarz\Config\Interfaces\Models\ConfigInterface;
use TheBachtiarz\Config\Interfaces\Repositories\ConfigRepositoryInterface;
use TheBachtiarz\Config\Interfaces\Services\ConfigServiceInterface;

class CreateSystemConfig extends CreateRecord
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

    protected function handleRecordCreation(array $data): Model
    {
        $this->processResponse = $this->configService->createOrUpdate(
            pathName: $data[ConfigInterface::ATTRIBUTE_PATH],
            value: $data[ConfigInterface::ATTRIBUTE_VALUE],
            isEncrypt: ConfigIsEncryptEnum::tryFrom(@$data[ConfigInterface::ATTRIBUTE_IS_ENCRYPT]) ?? ConfigIsEncryptEnum::FALSE,
        );

        if (!$this->processResponse->condition->value) {
            return new Model($data);
        }

        $entity = $this->configRepository->getByPath($this->processResponse->data[ConfigInterface::ATTRIBUTE_PATH]);

        assert($entity instanceof Model);

        return $entity;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->{$this->processResponse->condition->value ? 'success' : 'danger'}()
            ->title(sprintf('%s create new configs', $this->processResponse->condition->value ? 'Successfully' : 'Failed to'))
            ->body($this->processResponse->condition->value ? null : $this->processResponse->message)
            ->send();
    }
}
