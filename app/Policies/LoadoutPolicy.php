<?php

namespace App\Policies;

use App\Models\Loadout;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoadoutPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @return mixed
     */
    public function update(User $user, Loadout $loadout)
    {
        return $loadout->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return mixed
     */
    public function delete(User $user, Loadout $loadout)
    {
        return $loadout->user_id === $user->id;
    }
}
