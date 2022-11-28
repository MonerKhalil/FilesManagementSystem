<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function is_owner_group(User $user,Group $group): bool
    {
        return ($user->id == $group->id_user) || ($user->isAdmin());
    }

    public function add_delete_file_to_group(User $user,Group $group): bool
    {
        return ($user->id === $group->id_user) || ($group->users()->where("id_user",$user->id)->exists());
    }
    public function add_delete_users(User $user,Group $group): bool
    {
        return ($user->id === $group->id_user) || ($user->isAdmin());
    }

    public function delete_group(User $user,Group $group): bool
    {
        return ($user->id == $group->id_user) || ($user->isAdmin());
    }
}
