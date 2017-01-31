<?php

namespace Baytek\Laravel\Content\Webpage;

use Baytek\LaravelContent\Models\Content;

class Webpage extends Content
{
	protected $meta = [
		'author_id'
	];

	public function getRouteKeyName()
	{
	    return 'key';
	}
}
