<?php

namespace TheBachtiarz\Admin\Helpers\Model;

use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;

class AuthUserModelHelper
{
    /**
     * Get the list of admin identifiers.
     *
     * @return array<string>
     */
    public static function getAdminList(): array
    {
        $list = config(key: 'tbadmin.filament_admin_identifiers', default: []);

        return config(key: 'tboauth.auth_identifier_method') === AuthUserInterface::ATTRIBUTE_EMAIL
            ? array_keys($list)
            : array_values($list);
    }
}
