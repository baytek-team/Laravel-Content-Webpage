<?php

namespace Baytek\LaravelContentWebpage;

use Baytek\LaravelContent\Models\Content;
use Baytek\LaravelContent\Policies\ContentPolicy;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class ServiceProvider extends AuthServiceProvider
{

    protected $policies = [
        Content::class => ContentPolicy::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // AliasLoader::getInstance()->alias('Form', 'Collective\Html\FormFacade');
        $this->loadRoutesFrom(__DIR__.'/Routes.php');
        // $this->loadMigrationsFrom(__DIR__.'/../resources/Migrations');
        $this->loadViewsFrom(__DIR__.'/../src/Views', 'Webpage');
        Route::model('webpage', Baytek\LaravelContentWebpage\Webpage::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    	// $this->app->register('Collective\Html\HtmlServiceProvider');

    }
}