<?php

namespace App\Policies;

use App\Models\Giveaway;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GiveawayPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Giveaway  $giveaway
     * @return mixed
     */
    public function view(User $user, Giveaway $giveaway)
    {
        return $this->isOwner($user, $giveaway);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Giveaway  $giveaway
     * @return mixed
     */
    public function update(User $user, Giveaway $giveaway)
    {
        return $this->isOwner($user, $giveaway);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Giveaway  $giveaway
     * @return mixed
     */
    public function delete(User $user, Giveaway $giveaway)
    {
        return $this->isOwner($user, $giveaway);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Giveaway  $giveaway
     * @return mixed
     */
    public function restore(User $user, Giveaway $giveaway)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Giveaway  $giveaway
     * @return mixed
     */
    public function forceDelete(User $user, Giveaway $giveaway)
    {
        //
    }

    /**
     * Check if the user is the onwer of the giveaway
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Giveaway  $giveaway
     * @return bool
     */
    public function isOwner(User $user, Giveaway $giveaway): bool
    {
        return $user->id === $giveaway->user_id;
    }
}
