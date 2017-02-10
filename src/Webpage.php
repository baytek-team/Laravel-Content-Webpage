<?php

namespace Baytek\Laravel\Content\Types\Webpage;

use Baytek\Laravel\Content\Models\Content;

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
}
