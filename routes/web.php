<?php

use Illuminate\Support\Facades\Route;

Route::get('/',function(){
   return view('welcome');
});

Route::get('/w',function(){
//   return response()->json("sakmsaas");
   return "askmaskmsa";
});
