<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingFileController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth:user"]);
    }

    public function CheckIn(Request $request){

    }
    public function CheckOut(Request $request){

    }
    public function UpdateFile(Request $request){

    }
}
