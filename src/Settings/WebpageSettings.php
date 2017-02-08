<?php

namespace Baytek\Laravel\Content\Types\Webpage\Settings;

use Baytek\Laravel\Settings\Settings;
use Baytek\Laravel\Settings\Types\ArraySetting;
use Baytek\Laravel\Settings\Types\StringSetting;
use Baytek\Laravel\Settings\Types\IntegerSetting;

class WebpageSettings extends Settings
{
	protected $public = [
		'per_page',
		'background',
		'ordered',
		'order_by'
	];

	public function __construct()
	{
		$this->register([
			'background' => new StringSetting('', ['', 'red', 'blue', 'green', 'yellow']),
			'per_page' => new IntegerSetting(10),
			'order_by' => 'title',
			'ordered' => new StringSetting('desc', ['desc', 'asc']),
		]);
	}
}