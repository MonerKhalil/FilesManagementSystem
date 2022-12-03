<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User_File;
use App\MyApplication\MyApp;
use App\MyApplication\Services\FileRuleValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth:user"]);
        $this->middleware(["multi.auth:admin"])->only("All");
        $this->rules = new FileRuleValidation();
    }

    public function ReportFile(Request $request){
        $request->validate($this->rules->onlyKey(["id_file"],true));
        $file = File::with(["user"])
            ->where("files.id",$request->id_file)
            ->first();
        $report = User_File::query()
            ->select([
                "user_files.id_user as id_user_booking"
                ,"users.name as name_user_booking","user_files.created_at as booking_date"
                ,"user_files.deleted_at as unBooking_date"
            ])
            ->where("id_file",$file->id)
            ->join("users","users.id","=","id_user")
            ->orderBy("booking_date","desc")
            ->get();
        $this->authorize("is_owner_file",$file);
        $file->reports = $report;
        return $file;
    }

    public function All(): JsonResponse
    {
        return MyApp::Json()->dataHandle(File::with(["user","userBookings"])->get(),"files");
    }

    public function ShowMyFiles(): JsonResponse
    {
        $files = File::with("userBookings")->where("id_user",auth()->id())->get();
        return MyApp::Json()->dataHandle($files,"files");
    }

    public function CreateFile(Request $request): JsonResponse
    {
        $request->validate($this->rules->onlyKey(["id_group","name","file"],true));
        $file = $request->file("file");
        if ($file->isValid()){
            try {
                DB::beginTransaction();
                $path = MyApp::uploadFile()->upload($file);
                $fileAdded = File::create([
                    "id_user" => auth()->id(),
                    "name" => strtolower($request->name),
                    "path" => $path,
                ]);
                $fileAdded->groups()->syncWithoutDetaching($request->id_groupe);
                DB::commit();
                return MyApp::Json()->dataHandle($fileAdded,"file");
            }catch (\Exception $e){
                MyApp::uploadFile()->rollBackUpload();
                DB::rollBack();
                throw new \Exception($e->getMessage(),$e->getCode());
            }
        }else{
            return MyApp::Json()->errorHandle("file",$file->getError(),$file->getError());
        }
    }

    public function DeleteFile(Request $request): JsonResponse
    {
        $request->validate($this->rules->onlyKey(["id_file"],true));
        $file = File::where("id",$request->id_file)->first();
        $this->authorize("delete_file",$file);
        if ($file->CheckisBooking()){
            return MyApp::Json()->errorHandle("file","the File current is booking .");
        }
        DB::beginTransaction();
        $temp_path = $file->path;
        $file->delete();
        if (MyApp::uploadFile()->deleteFile($temp_path)){
            DB::commit();
            return MyApp::Json()->dataHandle("Successfully deleted file","message");
        }
        DB::rollBack();
        return MyApp::Json()->errorHandle("file","the File current is not deleted .");
    }
}
