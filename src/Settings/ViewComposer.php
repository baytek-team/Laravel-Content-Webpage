<?php

namespace Baytek\Laravel\Content\Types\Webpage\Settings;

use Illuminate\View\View;

class ViewComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $settings;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $settings
     * @return void
     */
    public function __construct(WebpageSettings $settings)
    {
        $packageSettings = collect($settings->getSettings());
        $appSettings = collect(config('webpage'));

        // This is where we need to check to see if the logged in user has any saved settings
        $userSettings = collect([]);

        $this->settings = $packageSettings->merge($appSettings)->merge($userSettings);

        // Dependencies automatically resolved by service container...
        // $this->settings = $settings;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('settings', $this->settings);
    }
}