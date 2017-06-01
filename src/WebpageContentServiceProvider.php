<?php

namespace Baytek\Laravel\Content\Types\Webpage;

use Baytek\Laravel\Content\ContentServiceProvider;
use Baytek\Laravel\Content\Models\Content;
use Baytek\Laravel\Content\Types\Webpage\Policies\WebpagePolicy;
use Baytek\Laravel\Content\Types\Webpage\Settings\WebpageSettings;
use Baytek\Laravel\Content\Types\Webpage\Webpage;
use Baytek\Laravel\Content\Types\Webpage\WebpageInstaller;
use Baytek\Laravel\Settings\Settable;
use Baytek\Laravel\Settings\SettingsProvider;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class WebpageContentServiceProvider extends AuthServiceProvider
{
    use Settable;

    /**
     * List of permission policies used by this package
     * @var [type]
     */
    protected $policies = [
        Webpage::class => WebpagePolicy::class,
    ];

    /**
     * List of artisan commands provided by this package
     * @var Array
     */
    protected $commands = [
        Commands\WebpageInstaller::class,
    ];

    /**
     * List of settings classes required by this package
     * @var Array
     */
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
        $this->registerPolicies();

        // Register the settings
        $this->registerSettings($this->settings);

        // Set the local load path for views
        $this->loadViewsFrom(__DIR__.'/../resources/Views', 'Webpage');

        // Set the path to publish assets for users to extend
        $this->publishes([
            __DIR__.'/../resources/Views' => resource_path('views/vendor/webpage'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../config/webpage.php' => config_path('webpage.php'),
        ], 'config');

        Broadcast::channel('content.{contentId}', function ($user, $contentId) {
            return true;//$user->id === Content::findOrNew($contentId)->user_id;
        });

        $this->bootRoutes();
    }

    public function bootRoutes()
    {
        // Set local namespace and make sure the route bindings occur
        // if(false) { // We should have a check if we need a wildcard route, and insert after all other routes. Must be last route.
        if(config('webpage.enabled')) {
            Route::group([
                    'namespace' => \Baytek\Laravel\Content\Types\Webpage::class,
                    'middleware' => ['web'],
                ], function ($router) {
                    // Add the default route to the routes list for this provider
                    $router->resource('admin/webpage', 'WebpageController');
                    $router->get('{url}', 'WebpageController@render')->where('url', '.*?');

                    $router->bind('url', function ($slug) {
                        $webpage = null;

                        // Get the cache route id where slug
                        if($id = collect(Cache::get('baytek.laravel.webpage.urls', []))->get($slug, false)) {
                            $webpage = Webpage::find($id);
                        }
                        // Try to load the content via another method
                        else {
                            // Try to find the page with the slug, this should also check its parents and should also split on /
                            $segments = collect(explode('/', $slug));
                            $webpages = Webpage::where('contents.key', $segments->last())->ofContentType('webpage')->get();

                            // Here I try to see if the url parents is the same as the url segments
                            $webpages->each(function ($page) use ($segments, &$webpage) {

                                // Get a list of the parents of current object
                                $pages = collect($page->getParents());

                                // If there is no difference of the result of the query and the URL segments we have a match
                                if($segments->diff($pages->pluck('key'))->isEmpty()) {
                                    // Set the webpage to the match
                                    $webpage = $page;

                                    // Cache the URL
                                    $webpage->cacheUrl();

                                    // Stop Processing
                                    return false;
                                }
                            });
                        }

                        // Show the 404 page if not found
                        if(is_null($webpage)) {
                            //abort(404);
                        }

                        return $webpage;
                    });
                });
        }
    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Register commands
        $this->commands($this->commands);

        $this->app->register(ContentServiceProvider::class);
    }
}
