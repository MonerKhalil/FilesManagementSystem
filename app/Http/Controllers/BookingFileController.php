<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User_File;
use App\MyApplication\MyApp;
use App\MyApplication\Services\FileRuleValidation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BookingFileController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth:user"]);
        $this->rules = new FileRuleValidation();
    }

    public function CheckIn(Request $request): JsonResponse
    {
        $request->validate([
            "ids" => ["required","array"],
            "ids.*" => ["numeric",Rule::exists("files","id")],
        ]);
        $user = auth()->user();
        $files = File::query()->whereIn("id",$request->ids)->get();
        DB::beginTransaction();
        foreach ($files as $file){
            if ($file->CheckisBooking()){
                DB::rollBack();
                return MyApp::Json()->errorHandle("file","you can't Check-in file [ $file->name ]becouse it was already booked .");
            }
            if (!$user->can("check_in_file",$file)){
                DB::rollBack();
                return MyApp::Json()->errorHandle("file","you can't Check-in file [ $file->name ] becouse you do not have the authority .");
            }
            $user->filesBookings()->attach($file->id);
        }
        DB::commit();
        return MyApp::Json()->dataHandle("Successfully Check-in files .","message");
    }

    public function CheckOut(Request $request): JsonResponse
    {
        $request->validate($this->rules->onlyKey(["id_file"],true));
        $file = User_File::query()->where("id_user",\auth()->id())
            ->where("id_file",$request->id_file)
            ->whereNull("deleted_at")->first();
        if (!is_null($file)){
            $file->update([
                "deleted_at" => Carbon::now()
            ]);
            return MyApp::Json()->dataHandle("Successfully Check-out file .","message");
        }
        return MyApp::Json()->errorHandle("file","you can't Check-out file becouse you do not Check-in .");
    }

    public function UpdateFile(Request $request){
        $request->validate($this->rules->onlyKey(["file","id_file"],true));

    }
}
