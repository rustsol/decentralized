<?php

namespace YTRFP\YTRFPLib;

class YTRFServiceProvider extends ServiceInitiate
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/main.php' => config_path('ytr_fin_po.php'),
        ]);

        $file = __DIR__.'/../vendor/autoload.php';

        if (file_exists($file)) {
            require $file;
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ytr_fin_po', function () {
            return new YTRF();
        });
    }
}
