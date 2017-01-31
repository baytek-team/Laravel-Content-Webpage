<?php

namespace Baytek\Laravel\Content\Types\Webpage;

use Baytek\Laravel\Content\Models\Content;
use Baytek\Laravel\Content\Policies\ContentPolicy;
use Baytek\Laravel\Content\Types\Webpage\Settings\ViewComposer;
use Baytek\Laravel\Content\Types\Webpage\Webpage;
use Baytek\Laravel\Content\Types\Webpage\Settings\WebpageSettings;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

use View;

class ServiceProvider extends AuthServiceProvider
{
    use \Baytek\Laravel\Settings\Settable;

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
        $this->registerSettings([
            'webpage' => WebpageSettings::class
        ]);

        $this->loadViewsFrom(__DIR__.'/../src/Views', 'Webpage');

        Route::group([
                'namespace' => \Baytek\Laravel\Content\Types\Webpage::class,
                'middleware' => SubstituteBindings::class,
            ], function ($router)
            {
                $router->get('{webpage}', '\Baytek\Laravel\Content\Types\Webpage\WebpageController@show');

                $router->bind('webpage', function($slug) {
                    $webpage = Webpage::where('key', $slug)->first();
                    if(is_null($webpage)) {
                        abort(404);
                    }
                    return $webpage;
                });
            });
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