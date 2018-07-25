<?php

namespace Galahad\LaravelAddressing;

use Galahad\LaravelAddressing\Validator\AdministrativeAreaValidator;
use Galahad\LaravelAddressing\Validator\CountryValidator;
use Galahad\LaravelAddressing\Validator\PostalCodeValidator;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\ServiceProvider as RootServiceProvider;

/**
 * Class ServiceProvider
 *
 * @author Chris Morrell
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class ServiceProvider extends RootServiceProvider
{
    /**
     * Booting the Service Provider
     */
    public function boot()
    {
        $this->bootRoutes();
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'laravel-addressing');
        $this->publishes([
            __DIR__.'/../config/addressing.php' => config_path('addressing.php'),
        ]);

        $this->registerValidators($this->app['validator']);
    }

    /**
     * Register the LaravelAddressing instance
     */
    public function register()
    {
        $this->app->singleton(LaravelAddressing::class, function ($app) {
            $config = $app->make('config');

            return new LaravelAddressing($config->get('app.locale', 'en'), $config->get('app.fallback_locale', 'en'));
        });
    }

    /**
     * Boot routes if routing is supported
     */
    protected function bootRoutes()
    {
        if (method_exists($this->app, 'routesAreCached') && $this->app->routesAreCached()) {
            return;
        }

        try {
            $route = $this->app->make('router');
            $prefix = config('addressing.route.prefix', 'galahad/addressing');
            $route->group(['prefix' => $prefix], function ($route) use ($prefix) {
                $route->get('/{country}/administrative-areas', [
                    'as' => 'galahad.addressing.administrative-areas',
                    'uses' => '\\Galahad\\LaravelAddressing\\Controller@getAdministrativeAreas',
                ]);
                $route->get('/{country}/{administrativeArea}/cities', [
                    'as' => 'galahad.addressing.cities',
                    'uses' => '\\Galahad\\LaravelAddressing\\Controller@getCities',
                ]);
                $route->get('/countries', [
                    'as' => 'galahad.addressing.countries',
                    'uses' => '\\Galahad\\LaravelAddressing\\Controller@getCountries',
                ]);
            });
        } catch (BindingResolutionException $exception) {
            // Skip routes if no router exists
        }
    }

    /**
     * Register all custom validators
     */
    protected function registerValidators(Factory $validator)
    {
        // Country validators
        $validator->extend('country_code', CountryValidator::class.'@validateCountryCode');
        $validator->extend('country_name', CountryValidator::class.'@validateCountryName');

        // Administrative Area validators
        $validator->extend(
            'administrative_area_code',
            AdministrativeAreaValidator::class.'@validateAdministrativeAreaCode'
        );
        $validator->extend(
            'administrative_area_name',
            AdministrativeAreaValidator::class.'@validateAdministrativeAreaName'
        );
        $validator->extend(
            'administrative_area',
            AdministrativeAreaValidator::class.'@validateAdministrativeArea'
        );

        // Postal Code validator
        $validator->extend('postal_code', PostalCodeValidator::class.'@validatePostalCode');
    }
}
