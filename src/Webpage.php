<?php

namespace Baytek\Laravel\Content\Types\Webpage;

use Baytek\Laravel\Content\Models\Content;

class Webpage extends Content
{
	protected $meta = [
		'author_id'
	];

	public $relationships = [
		'content-type' => 'webpage'
	];

	public function getRouteKeyName()
	{
	    return 'key';
	}
}
