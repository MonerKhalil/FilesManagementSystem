<?php

use App\Http\Controllers\AuthenticationController;
use App\MyApplication\MyApp;
use App\MyApplication\Role;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    echo Role::Admin->value;
//    dd(Role::Admin->value);
    \Illuminate\Support\Facades\DB::table("asmks")->get();
//    return MyApp::Json()->Paginate("moner");
    return MyApp::Json()->dataHandle(Role::Admin);
//    dd(Storage::disk("public")->path(''));
//    return view('welcome');
});

Route::get('/xx',[AuthenticationController::class,"Register"]);



Route::get('/tttt',function(){
   return view('welcome');
    response()->json(["usns"=>"askmkas"]);
    // return "null";
});
