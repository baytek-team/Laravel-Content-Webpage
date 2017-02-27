<?php

namespace Baytek\Laravel\Content\Types\Webpage\Policies;

use Baytek\Laravel\Users\User;
use Baytek\Laravel\Content\Types\Webpage\Webpage;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user is admin.
     *
     * @param  Baytek\Laravel\Users\User  $user
     * @return mixed
     */
    public function before(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the webpage.
     *
     * @param  Baytek\Laravel\Users\User  $user
     * @param  Baytek\Laravel\Content\Types\Webpage\Webpage  $webpage
     * @return mixed
     */
    public function view(User $user, Webpage $webpage)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create webpages.
     *
     * @param  Baytek\Laravel\Users\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can update the webpage.
     *
     * @param  Baytek\Laravel\Users\User  $user
     * @param  Baytek\Laravel\Content\Types\Webpage\Webpage  $webpage
     * @return mixed
     */
    public function update(User $user, Webpage $webpage)
    {
        //
        return true;
    }

    /**
     * Determine whether the user can delete the webpage.
     *
     * @param  Baytek\Laravel\Users\User  $user
     * @param  Baytek\Laravel\Content\Types\Webpage\Webpage  $webpage
     * @return mixed
     */
    public function delete(User $user, Webpage $webpage)
    {
        //
        return true;
    }
}
