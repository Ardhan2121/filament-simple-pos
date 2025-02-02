<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // URL::forceHttps();
        Model::unguard();
        Blade::directive('money', function ($amount) {
            return "<?php echo 'Rp' . number_format($amount, 0, ',', '.'); ?>";
        });
    }
}
