<?php

namespace Baytek\Laravel\Content\Webpage\Settings;

// use Baytek\LaravelSettings;

class WebpageSettings //extends Settings
{
	protected $public = [
		'per_page' => 10
	];

	public function getSettings()
	{
		return $this->public;
	}
}