<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group_User extends Model
{
    use HasFactory;
    protected $table = "group_users";
    protected $fillable = ['id_user','id_group'];
}
