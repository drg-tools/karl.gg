<?php

namespace App\Policies;

use App\Loadout;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoadoutPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\User $user
     * @param \App\Loadout $loadout
     * @return mixed
     */
    public function update(User $user, Loadout $loadout)
    {
        return $loadout->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\User $user
     * @param \App\Loadout $loadout
     * @return mixed
     */
    public function delete(User $user, Loadout $loadout)
    {
        return $loadout->user_id === $user->id;
    }
}
