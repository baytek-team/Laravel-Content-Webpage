<?php

use Baytek\Laravel\Content\Types\Webpage;

Route::get('{url}', 'WebpageController@render')->where('url', '.*?');
Route::bind('url', function ($slug) {
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