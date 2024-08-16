<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Destination;
use Illuminate\Auth\Access\HandlesAuthorization;

class DestinationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the destination.
     *
     * @param  \App\User  $user
     * @param  \App\Destination $destination
     * @return mixed
     */
    public function view(User $user, Destination $destination)
    {
        // Update $user authorization to view $destination here.
        return true;
    }

    /**
     * Determine whether the user can create destination.
     *
     * @param  \App\User  $user
     * @param  \App\Destination $destination
     * @return mixed
     */
    public function create(User $user, Destination $destination)
    {
        // Update $user authorization to create $destination here.
        return true;
    }

    /**
     * Determine whether the user can update the destination.
     *
     * @param  \App\User  $user
     * @param  \App\Destination $destination
     * @return mixed
     */
    public function update(User $user, Destination $destination)
    {
        // Update $user authorization to update $destination here.
        return true;
    }

    /**
     * Determine whether the user can delete the destination.
     *
     * @param  \App\User  $user
     * @param  \App\Destination $destination
     * @return mixed
     */
    public function delete(User $user, Destination $destination)
    {
        // Update $user authorization to delete $destination here.
        return true;
    }
}
