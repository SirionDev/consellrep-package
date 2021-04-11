<?php

namespace Siriondev\ConsellRepublica\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Siriondev\ConsellRepublica\IdentitatDigitalRepublicana;
use Siriondev\ConsellRepublica\Exceptions\ConsellRepublicaExceptionHandler;
use Siriondev\ConsellRepublica\Facades\IdentitatDigitalRepublicana as IdentitatDigitalRepublicanaFacade;

class ConsellRepublicaProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('IdentitatDigitalRepublicana', function() {
            return new IdentitatDigitalRepublicana();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishes();
        $this->registerValidators();
    }

    /**
     * Register custom exception handler.
     *
     * @return void
     */
    protected function registerPublishes()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'consellrep');

        $this->publishes([
            __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/consellrep/'),
        ], 'consellrep-translations');

        $this->publishes([
            __DIR__ . '/../../config/cxr.php' => config_path('cxr.php'),
        ], 'consellrep-config');

        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations')
        ], 'consellrep-migrations');

    }

    /**
     * Register custom exception handler.
     *
     * @return void
     */
    protected function registerValidators()
    {
        Validator::extend('idrepublicana', function($attribute, $value, $parameters) {
            return IdentitatDigitalRepublicanaFacade::validate($value);
        }, trans('consellrep::validation.idrepublicana'));
    }
}
