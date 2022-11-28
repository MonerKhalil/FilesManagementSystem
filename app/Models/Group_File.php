<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group_File extends Model
{
    use HasFactory;
    protected $table = "group_files";
    protected $fillable = ['id_group','id_file'];
}
