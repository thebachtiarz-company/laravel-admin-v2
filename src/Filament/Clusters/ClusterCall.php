<?php

namespace TheBachtiarz\Admin\Filament\Clusters;

use TheBachtiarz\Admin\Filament\Settings\FilamentDiscoverClass;

class ClusterCall extends FilamentDiscoverClass
{
    public static function dirname(): string
    {
        return dirname(__FILE__);
    }
}
