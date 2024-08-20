<?php

namespace TheBachtiarz\Admin\Providers;

use TheBachtiarz\Admin\Models\AuthUser;

class ConfigProvider
{
    public function __invoke(): void
    {
        $registerConfig = [];

        // ? Auth
        $registerConfig[] = [
            'auth.providers.users.model' => config('tboauth.auth_user_model', AuthUser::class),
        ];

        foreach ($registerConfig as $key => $config) {
            config($config);
        }
    }
}
