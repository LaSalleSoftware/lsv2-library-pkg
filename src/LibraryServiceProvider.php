<?php

/**
 * This file is part of the Lasalle Software library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019-2020 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 *
 * @see       https://lasallesoftware.ca
 * @see       https://packagist.org/packages/lasallesoftware/lsv2-library-pkg
 * @see       https://github.com/LaSalleSoftware/lsv2-library-pkg
 */

namespace Lasallesoftware\Library;

// LaSalle Software classes
use Lasallesoftware\Library\Authentication\CustomGuards\LasalleGuard;
use Lasallesoftware\Library\Authentication\Http\Middleware\RedirectSomeRoutes;
use Lasallesoftware\Library\Commands\CustomdropCommand;
use Lasallesoftware\Library\Commands\CustomseedCommand;
use Lasallesoftware\Library\Commands\DeleteExpiredLoginsCommand;
use Lasallesoftware\Library\Commands\InstalleddomainseedCommand;
use Lasallesoftware\Library\Commands\LasalleinstalladminappCommand;
use Lasallesoftware\Library\Commands\LasalleinstallenvCommand;
use Lasallesoftware\Library\Commands\LasalleinstallfrontendappCommand;

// Laravel Framework
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

// Laravel Nova 
use Laravel\Nova\Nova;


/**
 * Class LibraryServiceProvider.
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
     * (https://laravel.com/docs/5.6/providers#the-register-method)
     */
    public function register()
    {
        $this->app->singleton('lslibrary', function ($app) {
            return new LSLibrary();
        });

        $this->registerArtisanCommands();

        $this->registerCustomAuthenticationGuard();
    }

    /**
     * Bootstrap any package services.
     *
     * "So, what if we need to register a view composer within our service provider?
     * This should be done within the boot method. This method is called after all other service providers
     * have been registered, meaning you have access to all other services that have been registered by the framework"
     * (https://laravel.com/docs/5.6/providers)
     */
    public function boot(Router $router)
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

        $this->registerMiddlewareRouter($router);
        $this->registerMiddleware();
    }

    /**
     * Register middleware routes.
     *
     * @param Router $router
     */
    public function registerMiddlewareRouter($router)
    {
        $router->aliasMiddleware('whitelist', 'Lasallesoftware\Library\Firewall\Http\Middleware\Whitelist');

        // Add a middleware to the end of a middleware group
        // https://github.com/laravel/framework/blob/6.x/src/Illuminate/Routing/Router.php#L902
        $router->pushMiddlewareToGroup('web', 'whitelist');
    }

    /**
     * Register middleare
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        $kernel = $this->app->make(Kernel::class);
        $kernel->pushMiddleware(RedirectSomeRoutes::class);
    }

    /**
     * Register the artisan commands for this package.
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

        $this->app->bind('command.lslibrary:lasalleinstallenv', LasalleinstallenvCommand::class);
        $this->commands([
            'command.lslibrary:lasalleinstallenv',
        ]);

        $this->app->bind('command.lslibrary:lasalleinstalladminapp', LasalleinstalladminappCommand::class);
        $this->commands([
            'command.lslibrary:lasalleinstalladminapp',
        ]);

        $this->app->bind('command.lslibrary:lasalleinstallfrontendapp', LasalleinstallfrontendappCommand::class);
        $this->commands([
            'command.lslibrary:lasalleinstallfrontendapp',
        ]);

        $this->app->bind('command.lslibrary:deleteexpiredlogins', DeleteExpiredLoginsCommand::class);
        $this->commands([
            'command.lslibrary:deleteexpiredlogins',
        ]);
    }

    /**
     * Register the bindings for the custom authentication guard.
     *
     * Referenced https://github.com/tymondesigns/jwt-auth/blob/develop/src/Providers/AbstractServiceProvider.php#L96
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
     * Publish this package's configuration file.
     */
    protected function publishConfig()
    {
        $this->publishes([
            __DIR__.'/../config/lasallesoftware-library.php' => config_path('lasallesoftware-library.php'),
        ], 'config');
    }

    /**
     * Load this package's routes.
     */
    protected function loadRoutes()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/auth.php');
    }

    /**
     * Load this package's migrations.
     */
    protected function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Load this package's database factories.
     */
    protected function loadDatabaseFactories()
    {
        $this->app
            ->make('Illuminate\Database\Eloquent\Factory')
            ->load(__DIR__.'/../database/factories')
        ;
    }

    /**
     * Load this package's translations.
     */
    protected function loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__.'/../translations/', 'lasallesoftwarelibrary');
    }

    /**
     * Publish this package's translation files to the application's
     * resources/lang/vendor directory.
     */
    protected function publishTranslations()
    {
        $this->publishes([
            __DIR__.'/../translations' => resource_path('lang/vendor/lasallesoftwarelibrary'),
        ]);
    }

    /**
     * Load this package's views.
     */
    protected function loadViews()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'lasallesoftwarelibrary');
    }

    /**
     * Publish this package's views to the application's
     * resources/views/vendor directory.
     */
    protected function publishViews()
    {
        $this->publishes([
            __DIR__.'/../views' => resource_path('views/vendor/lasallesoftwarelibrary'),
        ], 'views');
    }

    /**
     * Merge config keys.
     */
    protected function mergeAuthGuardsConfigKey()
    {
        $path = __DIR__.'/../config/auth-guards.php';
        $this->mergeConfigFrom($path, 'auth.guards');
    }

    /**
     * Override the auth config file's defaults key.
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
