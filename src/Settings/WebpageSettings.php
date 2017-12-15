<?php

namespace Baytek\Laravel\Content\Types\Webpage\Settings;

use Baytek\Laravel\Settings\SettingsRegistrar;
use Baytek\Laravel\Settings\Types\ArraySetting;
use Baytek\Laravel\Settings\Types\StringSetting;
use Baytek\Laravel\Settings\Types\IntegerSetting;
use Baytek\Laravel\Settings\Types\BooleanSetting;

class WebpageSettings extends SettingsRegistrar
{
    /**
     * Public property defines a list of properties that can be managed by CMS admins or end users.
     *
     * Essentially these settings are treated as public settings
     *
     * @var Array
     */
    protected $public = [
        'per_page',
        'background',
        'enabled',
        'depth',
    ];

    /**
     * Webpage settings constructor.
     *
     * Here we register a list of settings objects.
     */
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
