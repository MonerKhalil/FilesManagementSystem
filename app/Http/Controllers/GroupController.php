<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\MyApplication\MyApp;
use App\MyApplication\RuleValidate;
use App\MyApplication\Services\GroupRuleValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth:user"])->except("All");
        $this->rules = new GroupRuleValidation();
    }

    public function All(): JsonResponse
    {
        return MyApp::Json()->dataHandle(Group::all(),"groups");
    }

    public function ShowMyGroups(): JsonResponse
    {
        return MyApp::Json()->dataHandle(Group::where("id_user",auth()->id())->get(),"groups");
    }

    public function ShowFilesGroup(Request $request): JsonResponse
    {
        $request->validate($this->rules->onlyKey(["id_group"]));
        $group = Group::with(["files"=>function($q){
            return $q->with("userBookings")->get();
        },])->where("id",$request->id_group)->first();
        $this->authorize("show_files_in_group",$group);
        return MyApp::Json()->dataHandle($group,"group");
    }

    public function CreateGroup(Request $request): JsonResponse
    {
        $request->validate($this->rules->except(["id_group"],true));
        $group = Group::create([
            "id_user" => auth()->id(),
            "name" => strtolower($request->name),
            "type" => $request->type
        ]);
        return MyApp::Json()->dataHandle($group,"group");
    }

    public function DeleteGroup(Request $request): JsonResponse
    {
        $request->validate($this->rules->onlyKey(["id_group"]));
        $group = Group::where("id",$request->id_group)->first();
        $this->authorize("delete_group",$group);
        if (!$group->CheckAnyFilesisBookings()){
            $group->delete();
            return MyApp::Json()->dataHandle("Successfully deleted group","message");
        }
        return MyApp::Json()->errorHandle("group","You cannot delete this group because it contains a non-free file .");
    }

//    public function UpdateGroup(Request $request){
//        $request->validate($this->rules->rules(true));
//        $group = Group::where("id",$request->id_group)->first();
//        $group->update([
//            "name" => $request->name ?? $group->name,
//            "type" => $request->type ?? $group->type,
//        ]);
//        return MyApp::Json()->dataHandle($group,"group");
//    }

}
