<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class File extends Model
{
    use HasFactory;
    protected $table = "files";
    protected $fillable = ['id_user','name','path'];
    protected $hidden = ['pivot'];

    public function user()
    {
        return $this->belongsTo(User::class,"id_user","id")->withDefault();
    }

    public function userBookings(){
        return $this->belongsToMany(User::class,"user_files",
            "id_file",
            "id_user",
            "id",
            "id"
        )->select(["users.id","users.name"]);
    }

    public function groups(){
        return $this->belongsToMany(Group::class,"group_files",
            "id_file",
            "id_group",
            "id",
            "id"
        );
    }

    public function CheckisBooking(){
        return !is_null($this->userBookings()->first());
    }

}
