<?php

namespace TheBachtiarz\Admin\DTOs\Filament\Configs;

use JsonSerializable;
use TheBachtiarz\Admin\Filament\Settings\FilamentDiscoverClass;

class DiscoverClassDTO implements JsonSerializable
{
    /**
     * @param class-string<FilamentDiscoverClass> $class Class file discovers
     * @param string $path File location based on directory address
     * @param string $namespace Target class namespace
     */
    public function __construct(
        public ?string $class = null,
        public ?string $path = null,
        public ?string $namespace = null,
    ) {
        if ($this->class) {
            $this->path ??= $this->class::dirname();
            $this->namespace ??= $this->class::dirClass();
        }
    }

    public function jsonSerialize(): mixed
    {
        return [
            'path' => $this->path,
            'namespace' => $this->namespace,
        ];
    }
}
