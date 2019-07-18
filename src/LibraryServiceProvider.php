<?php

/**
 * This file is part of the Lasalle Software library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/lsv2-library-pkg
 * @link       https://github.com/LaSalleSoftware/lsv2-library-pkg
 *
 */

namespace Lasallesoftware\Library;

// LaSalle Software classes
// custom artisan commands
use Lasallesoftware\Library\Commands\CustomseedCommand;
use Lasallesoftware\Library\Commands\CustomdropCommand;
use Lasallesoftware\Library\Commands\InstalleddomainseedCommand;
use Lasallesoftware\Library\Commands\LasalleinstallCommand;

// custom guard class
use Lasallesoftware\Library\Authentication\CustomGuards\LasalleGuard;

// model class
use Lasallesoftware\Library\Profiles\Models\Person;

// observer class
use Lasallesoftware\Library\Observers\PersonObserver;

// Laravel class
// https://github.com/laravel/framework/blob/5.6/src/Illuminate/Support/ServiceProvider.php
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

// Laravel Nova class
use Laravel\Nova\Nova;


// see https://laravel.com/docs/5.7/packages

/**
 * Class LibraryServiceProvider
 *
 * @package Lasallesoftware\Library
 */
class LibraryServiceProvider extends ServiceProvider
{
    use LibraryPoliciesServiceProvider;


    /**
     * Register any application services.
     *
     * "Within the register method, you should only bind things into the service container.
     * You should never attempt to register any event listeners, routes, or any other piece of functionality within
     * the register method. Otherwise, you may accidentally use a service that is provided by a service provider
     * which has not loaded yet."
     * (https://laravel.com/docs/5.6/providers#the-register-method(
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('lslibrary', function ($app) {
            return new LSLibrary();
        });

        $this->registerArtisanCommands();

        $this->registerNovaResources();

        $this->registerCustomAuthenticationGuard();
    }

    /**
     * Register the artisan commands for this package.
     *
     * @return void
     */
    protected function registerArtisanCommands()
    {
        $this->app->bind('command.lslibrary:customseeder', CustomseedCommand::class);
        $this->commands([
            'command.lslibrary:customseeder',
        ]);

        $this->app->bind('command.lslibrary:customdrop', CustomdropCommand::class);
        $this->commands([
            'command.lslibrary:customdrop',
        ]);

        $this->app->bind('command.lslibrary:installeddomainseeder', InstalleddomainseedCommand::class);
        $this->commands([
            'command.lslibrary:installeddomainseeder',
        ]);

        $this->app->bind('command.lslibrary:lasalleinstall', LasalleinstallCommand::class);
        $this->commands([
            'command.lslibrary:lasalleinstall',
        ]);
    }

    /**
     * Register the bindings for the custom authentication guard.
     *
     * Referenced https://github.com/tymondesigns/jwt-auth/blob/develop/src/Providers/AbstractServiceProvider.php#L96
     *
     * @return void
     */
    protected function registerCustomAuthenticationGuard()
    {
        $this->app['auth']->extend('lasalle', function ($app, $name, array $config) {

            $guard = new LasalleGuard(
                'session',
                $app['auth']->createUserProvider($config['provider']),
                //$app['request']->session(),
                $this->app['session.store'],
                $app['request'],
                $app->make('Lasallesoftware\Library\Authentication\Models\Login')
            );
            $app->refresh('request', $guard, 'setRequest');
            return $guard;
        });
    }

    /**
     * Register the Nova resources for this package.
     *
     * @return void
     */
    protected function registerNovaResources()
    {
        Nova::resources([
            \Lasallesoftware\Library\Nova\Resources\Address::class,
            \Lasallesoftware\Library\Nova\Resources\Company::class,
            \Lasallesoftware\Library\Nova\Resources\Email::class,
            \Lasallesoftware\Library\Nova\Resources\Lookup_address_type::class,
            \Lasallesoftware\Library\Nova\Resources\Installed_domain::class,
            \Lasallesoftware\Library\Nova\Resources\Lookup_email_type::class,
            \Lasallesoftware\Library\Nova\Resources\Lookup_lasallesoftware_event::class,
            \Lasallesoftware\Library\Nova\Resources\Lookup_role::class,
            \Lasallesoftware\Library\Nova\Resources\Lookup_social_type::class,
            \Lasallesoftware\Library\Nova\Resources\Lookup_telephone_type::class,
            \Lasallesoftware\Library\Nova\Resources\Lookup_website_type::class,
            \Lasallesoftware\Library\Nova\Resources\Person::class,
            \Lasallesoftware\Library\Nova\Resources\Personbydomain::class,
            \Lasallesoftware\Library\Nova\Resources\Social::class,
            \Lasallesoftware\Library\Nova\Resources\Telephone::class,
            \Lasallesoftware\Library\Nova\Resources\Website::class,
        ]);
    }


    /**
     * Bootstrap any package services.
     *
     * "So, what if we need to register a view composer within our service provider?
     * This should be done within the boot method. This method is called after all other service providers
     * have been registered, meaning you have access to all other services that have been registered by the framework"
     * (https://laravel.com/docs/5.6/providers)
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfig();

        $this->loadRoutes();

        $this->loadMigrations();
        $this->loadDatabaseFactories();

        $this->loadTranslations();
        $this->publishTranslations();

        $this->loadViews();
        //$this->publishViews();

        $this->registerPolicies();

        // Decided to directly edit the app's config/auth.php instead of modifying them here
        //$this->mergeAuthGuardsConfigKey();
        //$this->overrideDefaultAuthConfigKey();
    }

    /**
     * Publish this package's configuration file
     *
     * @return void
     */
    protected function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/lasallesoftware-library.php' => config_path('lasallesoftware-library.php'),
        ], 'config');
    }

    /**
     * Load this package's routes
     *
     * @return void
     */
    protected function loadRoutes()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/auth.php');
    }

    /**
     * Load this package's migrations
     *
     * @return void
     */
    protected function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Load this package's database factories
     *
     * @return void
     */
    protected function loadDatabaseFactories()
    {
        $this->app
            ->make('Illuminate\Database\Eloquent\Factory')
            ->load(__DIR__ . '/../database/factories');
    }

    /**
     * Load this package's translations
     *
     * @return void
     */
    protected function loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__.'/../translations/', 'lasallesoftwarelibrary');
    }

    /**
     * Publish this package's translation files to the application's
     * resources/lang/vendor directory
     *
     * @return void
     */
    protected function publishTranslations()
    {
        $this->publishes([
            __DIR__.'/../translations' => resource_path('lang/vendor/lasallesoftwarelibrary'),
        ]);
    }

    /**
     * Load this package's views
     *
     * @return void
     */
    protected function loadViews()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'lasallesoftwarelibrary');
    }

    /**
     * Publish this package's views to the application's
     * resources/views/vendor directory
     *
     * @return void
     */
    protected function publishViews()
    {
        $this->publishes([
            __DIR__.'/../views' => resource_path('views/vendor/lasallesoftwarelibrary'),
        ], 'views');
    }

    /**
     * Merge config keys
     *
     * @return void
     */
    protected function mergeAuthGuardsConfigKey()
    {
        $path = __DIR__ . '/../config/auth-guards.php';
        $this->mergeConfigFrom($path, 'auth.guards');
    }

    /**
     * Override the auth config file's defaults key
     *
     * @return void
     */
    protected function overrideDefaultAuthConfigKey()
    {
        $newDefaultAuth = [
            'guard' => 'lasalle',
            'passwords' => 'users',
        ];

        $this->app['config']->set('auth.defaults', $newDefaultAuth);
    }
}
