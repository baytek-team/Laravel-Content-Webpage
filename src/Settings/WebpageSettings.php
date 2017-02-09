<?php

namespace Baytek\Laravel\Content\Types\Webpage\Settings;

use Baytek\Laravel\Settings\Settings;
use Baytek\Laravel\Settings\Types\ArraySetting;
use Baytek\Laravel\Settings\Types\StringSetting;
use Baytek\Laravel\Settings\Types\IntegerSetting;
use Baytek\Laravel\Settings\Types\BooleanSetting;

class WebpageSettings extends Settings
{
	protected $public = [
		'per_page',
		'background',
		'enabled',
		'depth',
	];

	public function __construct()
	{
		$this->register([
			'depth' => new ArraySetting([
				'value' => [
					'resting' => new BooleanSetting(false),
				]
			]),
			'background' => new StringSetting([
				'value' => '',
				'possibilities' => ['', 'red', 'blue', 'green', 'yellow'],
				'type' => 'select',
			]),
			'per_page' => new IntegerSetting([
				'value' => 10,
				'min' => 0,
				'max' => 100,
			]),
			'enabled' => new BooleanSetting([
				'value' => true
			]),
			'ordered' => new StringSetting('desc', ['desc', 'asc']),
		]);
	}
}

