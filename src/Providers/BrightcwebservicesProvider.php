<?php

namespace Brightcweb\Paypal\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class BrightcwebservicesProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {  
       // dd(config("brightpaypalconfig.client_id"));
        $this->loadRoutesFrom(__DIR__.'/../routes/brightcwebpaypalroutes.php');
     //   $this->loadViewsFrom(__DIR__.'/../resources/views', 'brightcwebpaypal');
     $this->copyViewsToApp();
        // publish view
        
        $this->publishes([
            __DIR__.'/../config/brightpaypalconfig.php' => config_path("brightpaypalconfig.php"),
        ], 'brightpaypalconfig');
    }


public function copyViewsToApp()
{
    $sourcePath = __DIR__.'/../resources/views';
    $destinationPath = resource_path('views/vendor/brightcwebpaypal');
    $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

    // Ensure the destination folder exists
    if (!File::exists($destinationPath)) {
        File::makeDirectory($destinationPath, 0755, true);
    }

    // Copy views
    $views = File::allFiles($sourcePath);

    foreach ($views as $view) {
        $destination = $destinationPath . '/' . $view->getFilename();
        File::copy($view->getRealPath(), $destination);
    }
}

}
