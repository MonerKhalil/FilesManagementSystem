<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Group extends Model
{
    use HasFactory;
    protected $table = "groups";
    protected $fillable = ['id_user','name','type'];
    protected $hidden = ['pivot','id_user'];

    public function user()
    {
        return $this->belongsTo(User::class,"id_user","id")->select(["users.id","users.name"])->withDefault();
    }
    public function users(){
        return $this->belongsToMany(User::class,"group_users",
            "id_group",
            "id_user",
            "id",
            "id"
        );
    }
    public function files(){
        return $this->belongsToMany(File::class,"group_files",
            "id_group",
            "id_file",
            "id",
            "id"
        );
    }

    public function CheckAnyFilesisBookings(): bool
    {
        $myfiles = $this->files()->pluck("id_file");
        return User_File::query()->whereNull("deleted_at")
            ->whereIn("id_file",$myfiles)->exists();
    }

    public function addUsers(array $ids_user){
//        foreach ($ids_user as $id){
//            if (User::query()->where("id",$id)->exists()){
//                $this->users()->syncWithoutDetaching($id);
//            }
//        }
        DB::transaction(function () use ($ids_user){
            $this->users()->syncWithoutDetaching($ids_user);
        });
    }

    public function deleteUsersinGroup(array $ids_user){
//        DB::transaction(function () use ($ids_user){
//            foreach ($ids_user as $id){
//                if (!$this->checkUserBookingFileinGroup($id)){
//                    $this->users()->detach($id);
//                }
//            }
//        });
        DB::transaction(function () use ($ids_user){
            $myfiles = $this->files()->pluck("id_file");
            User_File::query()
                ->whereIn("id_user",$ids_user)
                ->whereIn("id_file",$myfiles)
                ->whereNull("deleted_at")
                ->delete();
        });
    }

//    public function checkUserBookingFileinGroup($id_user): bool
//    {
//        $myfiles = $this->files()->pluck("id_file");
//        return User_File::query()
//            ->where("id_user",$id_user)
//            ->whereIn("id_file",$myfiles)
//            ->whereNull("deleted_at")
//            ->exists();
//    }

    public function isPublic(): bool
    {
        return $this->type === "public";
    }

}
