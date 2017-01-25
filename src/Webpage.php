<?php

namespace Baytek\LaravelContentWebpage;

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
