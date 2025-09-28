<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GlobalFunctionsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        require_once base_path() . '/app/Functions/GlobalFunctions.php';
    }

    public function boot(): void
    {
        //
    }
}
