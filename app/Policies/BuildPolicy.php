<?php

namespace App\Policies;

use App\Build;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuildPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Build  $build
     * @return mixed
     */
    public function update(User $user, Build $build)
    {
        return $build->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Build  $build
     * @return mixed
     */
    public function delete(User $user, Build $build)
    {
        //
    }
}
