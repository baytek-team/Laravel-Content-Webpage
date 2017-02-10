<?php

namespace Baytek\Laravel\Content\Types\Webpage;

use Baytek\Laravel\Content\Models\Content;
use Baytek\Laravel\Content\Policies\ContentPolicy;
use Baytek\Laravel\Content\Types\Webpage\Settings\WebpageSettings;
use Baytek\Laravel\Content\Types\Webpage\Webpage;
use Baytek\Laravel\Settings\Settable;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Baytek\Laravel\Settings\SettingsProvider;

use View;

class ServiceProvider extends AuthServiceProvider
{
    use Settable;

    protected $policies = [
        Content::class => ContentPolicy::class,
    ];

    protected $settings = [
        'webpage' => WebpageSettings::class
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register the settings
        $this->registerSettings($this->settings);

        // Set the local load path for views
        $this->loadViewsFrom(__DIR__.'/../src/Views', 'Webpage');

        // Set local namespace and make sure the route bindings occur
        Route::group([
                'namespace' => \Baytek\Laravel\Content\Types\Webpage::class,
                'middleware' => ['web'],
            ], function ($router)
            {
                // Add the default route to the routes list for this provider
                $router->resource('admin/webpage', 'WebpageController');
                $router->get('{webpage}', 'WebpageController@show');

                $router->bind('webpage', function($slug)
                {
                    // Try to find the page with the slug, this should also check its parents and should also split on /
                    $webpage = Webpage::where('contents.key', $slug)->ofContentType('webpage')->first();

                    // Show the 404 page if not found
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