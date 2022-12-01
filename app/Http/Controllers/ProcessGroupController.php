<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Group;
use App\MyApplication\MyApp;
use App\MyApplication\Services\FileRuleValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ProcessGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth:user"]);
        $this->rules = new FileRuleValidation();
    }

    public function AddFiletoGroup(Request $request): JsonResponse
    {
        $request->validate($this->rules->onlyKey(["id_group","id_file"],true));
        $file = File::where("id",$request->id_file)->first();
        $this->authorize("is_owner_file",$file);
        $group = Group::query()->where("id",$request->id_group)->first();
        $this->authorize("add_file_to_group",$group);
        DB::transaction(function () use ($file,$group){
            $file->groups()->syncWithoutDetaching($group->id);
        });
        return MyApp::Json()->dataHandle("Successfully add file to group","message");
    }

    public function DeleteFileinGroup(Request $request): JsonResponse
    {
        $request->validate($this->rules->onlyKey(["id_group","id_file"],true));
        $file = File::where("id",$request->id_file)->first();
        $this->authorize("is_owner_file",$file);
//        $group = Group::query()->where("id",$request->id_group)->first();
//        $this->authorize("add_file_to_group",$group);
        DB::transaction(function () use ($file,$request){
            $file->groups()->detach($request->id_group);
        });
        return MyApp::Json()->dataHandle("Successfully delete file From group","message");
    }

    public function AddUserstoGroup(Request $request): JsonResponse
    {
        $request->validate($this->rules->onlyKey(["id_group","ids_user"],true));
        $group = Group::where("id",$request->id_group)->first();
        $this->authorize("add_delete_users",$group);
        $group->addUsers($request->ids_user);
        return MyApp::Json()->dataHandle("Successfully added users to group","message");
    }

    public function DeleteUserstoGroup(Request $request): JsonResponse
    {
        $request->validate($this->rules->onlyKey(["id_group","ids_user"],true));
        $group = Group::where("id",$request->id_group)->first();
        $this->authorize("add_delete_users",$group);
        $group->deleteUsersinGroup($request->ids_user);
        return MyApp::Json()->dataHandle("Successfully deleted users From group","message");
    }
}
