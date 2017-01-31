<?php

namespace Baytek\Laravel\Content\Webpage;

use Baytek\Laravel\Content\Models\Content;

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
