<?php

namespace Baytek\Laravel\Content\Types\Webpage;

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

	public function getRouteKeyName()
	{
	    return 'key';
	}

	public function cacheUrl()
    {
        $url = collect($this->getParents())->pluck('key')->implode('/');

        $urls = Cache::get('baytek.laravel.webpage.urls', []);

        $urls[$url] = $this->id;

        Cache::forever('baytek.laravel.webpage.urls', $urls);

        return $this;
    }
}
