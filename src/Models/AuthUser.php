<?php

namespace TheBachtiarz\Admin\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use TheBachtiarz\Admin\Helpers\Model\AuthUserModelHelper;
use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;

class AuthUser extends \TheBachtiarz\OAuth\Models\AuthUser implements AuthUserInterface, FilamentUser, HasAvatar
{
    /**
     * Constructor
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct(attributes: $attributes);
    }

    // ? Public Methods

    public function name(): Attribute
    {
        return new Attribute(
            get: fn(): string => $this->getIdentifier(),
        );
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array(needle: $this->getIdentifier(), haystack: AuthUserModelHelper::getAdminList());
    }

    public function getFilamentAvatarUrl(): ?string
    {
        $identifierPrefix = explode(separator: '@', string: $this->getIdentifier())[0];
        $letterColor = 'FFFFFF';
        $bgColor = sprintf('%06X', mt_rand(0, 0xFFFFFF));

        return sprintf(
            'https://ui-avatars.com/api/?name=%s&color=%s&background=%s',
            $identifierPrefix,
            $letterColor,
            $bgColor,
        );
    }

    // ? Protected Methods

    // ? Private Methods

    // ? Getter Modules

    // ? Setter Modules
}
