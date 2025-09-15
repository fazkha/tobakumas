<?php

namespace App\Providers;

use App\Models\PurchaseOrder;
use App\Models\StockOpname;
use App\Policies\PurchaseReceiptPolicy;
use App\Policies\StockOpnamePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelPdf\PdfFactory;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        app()->bind(PdfFactory::class, function ($service, $app) {
            return (new PdfFactory())->withBrowsershot(
                function ($browserShot) {
                    $browserShot->setOption(
                        'args',
                        [
                            '--disable-web-security',
                            '--allow-file-access-from-files',
                        ],
                    );
                }
            );
        });

        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        Gate::policy(PurchaseOrder::class, PurchaseReceiptPolicy::class);
        Gate::policy(StockOpname::class, StockOpnamePolicy::class);
    }
}
