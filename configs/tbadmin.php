<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Filament Cluster Definer
    |--------------------------------------------------------------------------
    |
    | Define cluster(s) directory/path for filament.
    | @var array<int,array<string,\TheBachtiarz\Admin\Filament\Settings\FilamentDiscoverClass>>
    |
    */
    'filament_discover_clusters' => [
        ['class' => \TheBachtiarz\Admin\Filament\Clusters\ClusterCall::class],
    ],

    /*
    |--------------------------------------------------------------------------
    | Filament Page Definer
    |--------------------------------------------------------------------------
    |
    | Define page(s) directory/path for filament.
    | @var array<int,array<string,\TheBachtiarz\Admin\Filament\Settings\FilamentDiscoverClass>>
    |
    */
    'filament_discover_pages' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Filament Resource Definer
    |--------------------------------------------------------------------------
    |
    | Define resource(s) directory/path for filament.
    | @var array<int,array<string,\TheBachtiarz\Admin\Filament\Settings\FilamentDiscoverClass>>
    |
    */
    'filament_discover_resources' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Filament Widget Definer
    |--------------------------------------------------------------------------
    |
    | Define widget(s) directory/path for filament.
    | @var array<int,array<string,\TheBachtiarz\Admin\Filament\Settings\FilamentDiscoverClass>>
    |
    */
    'filament_discover_widgets' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Filament Page Custom
    |--------------------------------------------------------------------------
    |
    | Define custom page(s) for filament.
    | @var array<class-string>
    |
    */
    'filament_custom_pages' => [
        \Filament\Pages\Dashboard::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Filament Resource Custom
    |--------------------------------------------------------------------------
    |
    | Define custom resource(s) for filament.
    | @var array<class-string>
    |
    */
    'filament_custom_resources' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Filament Widget Custom
    |--------------------------------------------------------------------------
    |
    | Define custom widget(s) for filament.
    | @var array<class-string>
    |
    */
    'filament_custom_widgets' => [
        \Filament\Widgets\AccountWidget::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Filament Admin URI
    |--------------------------------------------------------------------------
    |
    | Define admin base url for filament.
    | @var string
    |
    */
    'filament_admin_uri' => 'system-admin',

    /*
    |--------------------------------------------------------------------------
    | Filament Admin Identifier
    |--------------------------------------------------------------------------
    |
    | Define admin identifier(s) for filament credential.
    | @var array<string,string>
    |
    */
    'filament_admin_identifiers' => [
        'super@admin.com' => 'SuperAdmin321',
    ],

    /*
    |--------------------------------------------------------------------------
    | Filament Admin Password
    |--------------------------------------------------------------------------
    |
    | Define default admin password for filament credential.
    | @var string
    |
    */
    'filament_admin_password' => '&Secret67890',
];
