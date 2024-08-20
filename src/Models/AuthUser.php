<?php

namespace TheBachtiarz\Admin\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;
use TheBachtiarz\OAuth\Models\AuthUser as OauthAuthUser;

class AuthUser extends OauthAuthUser implements AuthUserInterface, FilamentUser, HasAvatar
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
        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        $identifierPrefix = explode(separator: '@', string: $this->getIdentifier())[0];
        $letterColor = 'FFFFFF';
        $bgColor = str(fake()->hexColor())->trim('#');

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
