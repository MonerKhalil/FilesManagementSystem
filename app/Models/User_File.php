<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_File extends Model
{
    use HasFactory;
    protected $table = "user_files";
    protected $fillable = ['id_user','id_file','deleted_at'];
}
