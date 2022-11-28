<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProcessGroupController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::prefix("filemanagement")->group(function (){
    Route::prefix("auth")->controller(AuthenticationController::class)->group(function (){
        Route::get("user","MyData");
        Route::post("register","Register");
        Route::post("login","Login");
        Route::delete("logout","Logout");
    });
    Route::prefix("group")->group(function (){
        Route::controller(GroupController::class)->group(function (){
            Route::get("all","All");
            Route::get("show","ShowMyGroups");
            Route::get("files/show","ShowFilesGroup");
            Route::post("create","CreateGroup");
            Route::delete("delete","DeleteGroup");
        });
        Route::controller(ProcessGroupController::class)->group(function (){
            Route::prefix("file")->group(function (){
                Route::post("add","AddFiletoGroup");
                Route::delete("delete","DeleteFileinGroup");
            });
            Route::prefix("users")->group(function (){
                Route::post("add","AddUserstoGroup");
                Route::delete("delete","DeleteUserstoGroup");
            });
        });
    });
    Route::prefix("file")->controller(FileController::class)->group(function (){
        Route::get("show","ShowMyFiles");
        Route::post("add","CreateFile");
        Route::delete("delete","DeleteFile");
    });
    Route::get("search/{type}",[SearchController::class,"Search"]);
});
