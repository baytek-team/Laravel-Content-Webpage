<?php

namespace Baytek\Laravel\Content\Types\Webpage\Settings;

use Baytek\Laravel\Settings\Settings;

class WebpageSettings extends Settings
{
	protected $public = [
		'per_page'
	];

	protected $settings = [
		'per_page' => 10,
		'background' => 'red',
		'stuff' => 'stuff'
	];
}