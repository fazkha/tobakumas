<?php

namespace App\Providers;

use App\Models\Satuan;
use App\Policies\SatuanPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Gate::policy(Satuan::class, SatuanPolicy::class);
    }
}
