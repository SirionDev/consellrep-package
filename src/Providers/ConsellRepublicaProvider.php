<?php

namespace Siriondev\ConsellRepublica\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Siriondev\ConsellRepublica\IdentitatDigitalRepublicana;
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
        Validator::extend('idrepublicana', function($attribute, $value, $parameters, $validator) {

            $valid = true;

            $error = "";

            $idr_validator = IdentitatDigitalRepublicanaFacade::validate($value);

            if (empty($parameters))

                return $idr_validator->isValid() && $idr_validator->isActive() && $idr_validator->isFormat();

            foreach ($parameters as $parameter) {

                $parameter = preg_replace( '/[\W]/', '', $parameter);

                $method = 'is' . Str::ucfirst($parameter);

                if (is_callable(array($idr_validator, $method))) {

                    if (!$idr_validator->$method()) {

                        $valid = false;

                        $error = trans('consellrep::validation.idrepublicana.' . $parameter);

                        break;
                    }

                } else {

                    $valid = false;

                    $error = trans('consellrep::validation.idrepublicana.invalid_rule', ['rule' => $parameter]);

                    break;
                }
            }

            $validator->addReplacer('idrepublicana', function($message, $attribute, $rule, $parameters) use ($error) {

                return $error;

            });

            return $valid;
        });
    }
}
