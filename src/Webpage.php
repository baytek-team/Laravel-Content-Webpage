<?php

namespace Baytek\Laravel\Content\Types\Webpage;

use Baytek\Laravel\Content\Types\Webpage\Scopes\WebpageScope;
use Baytek\Laravel\Content\Models\Content;

use Cache;

class Webpage extends Content
{
	/**
	 * Meta keys that the content expects to save
	 * @var Array
	 */
	protected $meta = [
		'author_id'
	];

	/**
	 * Content keys that will be saved to the relation tables
	 * @var Array
	 */
	public $relationships = [
		'content-type' => 'webpage'
	];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        static::addGlobalScope(new WebpageScope);
        parent::boot();
    }

	public function getRouteKeyName()
	{
	    return 'id';
	}

	public function cacheUrl()
    {
        $url = collect($this->getParents())->pluck('key')->implode('/');

        $urls = Cache::get('baytek.laravel.webpage.urls', []);

        $urls[$url] = $this->id;

        Cache::forever('baytek.laravel.webpage.urls', $urls);

        return $this;
    }

    public function getUrl()
    {
    	$urls = array_flip(Cache::get('baytek.laravel.webpage.urls', []));

    	if(!array_key_exists($this->id, $urls)) {
    		$url = collect($this->getParents())->pluck('key')->implode('/');
    		$urls = array_flip($urls);
    		$urls[$url] = $this->id;
    		Cache::forever('baytek.laravel.webpage.urls', $urls);

    		return $url;
    	}
    	else {
    		return $urls[$this->id];
    	}
    }
}
