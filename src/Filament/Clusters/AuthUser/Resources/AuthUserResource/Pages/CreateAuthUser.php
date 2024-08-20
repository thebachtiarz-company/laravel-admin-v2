<?php

namespace TheBachtiarz\Admin\Filament\Clusters\AuthUser\Resources\AuthUserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use TheBachtiarz\Admin\Filament\Clusters\AuthUser\Resources\AuthUserResource;

class CreateAuthUser extends CreateRecord
{
    protected static string $resource = AuthUserResource::class;
}
