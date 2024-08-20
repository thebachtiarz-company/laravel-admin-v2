<?php

namespace TheBachtiarz\Admin\Providers;

use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends \Illuminate\Foundation\Support\Providers\RouteServiceProvider
{
    public function boot(): void
    {
        Route::middleware([])->group(__DIR__ . '/../routes/filament.php');
    }
}
