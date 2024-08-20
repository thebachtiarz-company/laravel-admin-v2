<?php

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

Route::get('', fn(): RedirectResponse => redirect(to: 'system-admin'))->name('login');
