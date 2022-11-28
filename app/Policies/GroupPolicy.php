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

    public function show_files_in_group(User $user,Group $group): bool
    {
        return $this->is_owner_group($user,$group) || $this->user_in_group($user,$group);
    }

    public function is_owner_group(User $user,Group $group): bool
    {
        return ($user->id == $group->id_user) || ($user->isAdmin());
    }

    public function user_in_group(User $user,Group $group):bool{
        return ($group->users()->where("id_user",$user->id)->exists());
    }

    public function add_delete_file_to_group(User $user,Group $group): bool
    {
        return $this->is_owner_group($user,$group) || $this->user_in_group($user,$group);
    }

    public function add_delete_users(User $user,Group $group): bool
    {
        return ($user->id === $group->id_user) || ($user->isAdmin());
    }

    public function delete_group(User $user,Group $group): bool
    {
        return $this->is_owner_group($user,$group);
    }
}
