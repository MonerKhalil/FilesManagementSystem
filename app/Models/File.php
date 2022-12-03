<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class File extends Model
{
    use HasFactory;
    protected $table = "files";
    protected $fillable = ['name','path'];
    protected $hidden = ['pivot','id_user'];

    public function user()
    {
        return $this->belongsTo(User::class,"id_user","id")->select(["users.id","users.name"])->withDefault();
    }

    public function userBookings(){
        return $this->belongsToMany(User::class,"user_files",
            "id_file",
            "id_user",
            "id",
            "id"
        )->select(["users.id","users.name"])->whereNull("user_files.deleted_at");
    }

    public function groups(){
        return $this->belongsToMany(Group::class,"group_files",
            "id_file",
            "id_group",
            "id",
            "id"
        );
    }

    public function CheckisBooking(): bool
    {
        return $this->userBookings()->exists();
    }

}
