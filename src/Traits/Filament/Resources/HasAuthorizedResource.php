<?php

namespace TheBachtiarz\Admin\Traits\Filament\Resources;

use Illuminate\Support\Facades\Auth;
use TheBachtiarz\OAuth\Helpers\AuthUserHelper;
use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;

/**
 * Has Authorized Resource
 */
trait HasAuthorizedResource
{
    public static function canAccess(): bool
    {
        if (!Auth::hasUser()) {
            return false;
        }

        $currentUser = Auth::user();
        assert($currentUser instanceof AuthUserInterface);

        $authorizedUsers = config(key: 'tbadmin.filament_admin_identifiers', default: []);

        foreach ($authorizedUsers as $email => $username) {
            if ($currentUser->getIdentifier() === ${AuthUserHelper::authMethod()}) {
                return true;
            }
        }

        return false;
    }
}
