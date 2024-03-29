<?php

namespace App\Policies;

use App\Models\DefaultCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DefaultCategoryPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        return $user->user_type == 'A';
    }
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DefaultCategory  $defaultCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, DefaultCategory $defaultCategory)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DefaultCategory  $defaultCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, DefaultCategory $defaultCategory)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DefaultCategory  $defaultCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, DefaultCategory $defaultCategory)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DefaultCategory  $defaultCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, DefaultCategory $defaultCategory)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DefaultCategory  $defaultCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, DefaultCategory $defaultCategory)
    {
        return true;
    }
}
