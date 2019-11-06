<?php

namespace Petecheyne\Passport;

use Laravel\Nova\Http\Controllers\ResourceStoreController;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Petecheyne\Passport\Http\Middleware\Authorize;
use Petecheyne\Passport\Nova\Client;
use Petecheyne\Passport\Nova\Token;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'passport');

        $this->app->booted(function () {
            $this->routes();

            Nova::resources([
                Client::class,
                Token::class,
            ]);
        });


        Nova::serving(function (ServingNova $event) {
            //
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/passport')
                ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->extend(ResourceStoreController::class, function () {
            return new Http\Controllers\ResourceStoreController;
        });
    }
}
