<?php

namespace Baytek\Laravel\Content\Types\Webpage\Policies;

use Baytek\Laravel\Content\Policies\GeneralPolicy;
use Baytek\Laravel\Content\Types\Webpage\Webpage;
use Baytek\Laravel\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WebpagePolicy extends GeneralPolicy
{
    public $contentType = 'Webpage';
}
