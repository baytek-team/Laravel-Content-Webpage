<?php

namespace Baytek\Laravel\Content\Types\Webpage;

use Baytek\Laravel\Content\Models\Content;
use Baytek\Laravel\Content\Policies\ContentPolicy;
use Baytek\Laravel\Content\Types\Webpage\Settings\ViewComposer;
use Baytek\Laravel\Content\Types\Webpage\Webpage;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

use View;


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
        $this->loadViewsFrom(__DIR__.'/../src/Views', 'Webpage');

        Route::group([
                'namespace' => \Baytek\Laravel\Content\Types\Webpage::class,
                'middleware' => SubstituteBindings::class,
            ], function ($router)
            {
                $router->get('{webpage}', '\Baytek\Laravel\Content\Types\Webpage\WebpageController@show');

                $router->bind('webpage', function($slug) {
                    return Webpage::where('key', $slug)->firstOrFail();
                });
            });

        View::composer(
            'Webpage::*', ViewComposer::class
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}