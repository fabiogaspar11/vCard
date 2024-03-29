<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vcard;
use Illuminate\Auth\Access\HandlesAuthorization;

class VcardPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->user_type == 'A';
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Vcard  $vcard
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Vcard $vcard)
    {
        if($user->user_type == 'V'){
            return $user->username == $vcard->phone_number;
        }
        return $user->user_type == 'A';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Vcard  $vcard
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Vcard $vcard)
    {
        if($user->user_type == 'V'){
            return $user->username == $vcard->phone_number;
        }
        return $user->user_type == 'A';
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Vcard  $vcard
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Vcard $vcard)
    {
        if($user->user_type == 'V'){
            return $user->username == $vcard->phone_number;
        }
        return $user->user_type == 'A';
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Vcard  $vcard
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Vcard $vcard)
    {
        return $user->user_type == 'A';
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Vcard  $vcard
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Vcard $vcard)
    {
        return $user->user_type == 'A';
    }
}
