<?php

namespace App\Providers;

use App\Models\PurchaseOrder;
use App\Policies\PurchaseReceiptPolicy;
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
        Gate::policy(PurchaseOrder::class, PurchaseReceiptPolicy::class);
    }
}
