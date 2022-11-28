<?php

namespace App\Policies;

use App\Models\File;
use App\Models\Group;
use App\Models\User;
use App\MyApplication\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function is_owner_file(User $user,File $file): bool
    {
       return ($user->id == $file->id_user) || ($user->isAdmin());
    }

    public function delete_file(User $user,File $file): bool
    {
        return $this->is_owner_file($user,$file);
    }

}
