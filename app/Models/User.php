<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\MyApplication\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password','role'
    ];

    protected $hidden = [
        'password','pivot',
        'remember_token','email_verified_at'
    ];

    public function Myfiles(){
        return $this->hasMany(File::class,"id_user","id");
    }

    public function Mygroups(){
        return $this->hasMany(Group::class,"id_user","id");
    }

    public function filesBookings(){
        return $this->belongsToMany(File::class,"user_files",
            "id_user",
            "id_file",
            "id",
            "id"
        )->withTimestamps();
    }

    public function userGroups(){
        return $this->belongsToMany(Group::class,"group_users",
            "id_user",
            "id_group",
            "id",
            "id"
        )->withTimestamps();
    }

    public function isAdmin(): bool
    {
        return $this->role === Role::Admin->value;
    }

    public function getWithNewToken(){
        $user = $this;
        $token = $user->createToken($user->name,["*"])->plainTextToken;
        $user->token = $token;
        return $this;
    }


}
