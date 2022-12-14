<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\MyApplication\MyApp;
use App\MyApplication\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuthenticationController extends Controller
{

    public function __construct()
    {
        $this->middleware(["admin.guest"])->only("Register");
        $this->middleware(["auth:user"])->only(["Logout","MyData"]);
    }

    public function Users(): JsonResponse
    {
        return MyApp::Json()->dataHandle(User::query()
            ->whereNot("role",Role::Admin->value)
            ->orderBy("id","desc")->get(),"users");
    }

    public function MyData(): JsonResponse
    {
        return MyApp::Json()->dataHandle(auth()->user(),"user");
    }

    public function Register(Request $request)
    {
        $request->validate([
            "name" => ["required","string"],
            "email" => ["required",Rule::unique("users","email"),"email"],
            "password" => ["required","min:8"],
            "role" => ["nullable","string",Rule::in([Role::Admin->value,Role::User->value])],
        ]);
        $role = Role::User->value;
        $user = auth("user")->user();
        if (!is_null($user)){
            $role = ($user->role===Role::Admin->value) && ($request->has("role")) ?
                $request->role : Role::User;
        }
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => password_hash($request->password,PASSWORD_DEFAULT),
            "role" => $role
        ]);
        return MyApp::Json()->dataHandle($user->getWithNewToken(),"user");
    }

    public function Login(Request $request)
    {
        $request->validate([
            "email" => ["required",Rule::exists("users","email")],
            "password" => ["required","min:8"],
        ]);
        $user = User::where("email",$request->email)->first();
        if (password_verify($request->password,$user->password)){
            return MyApp::Json()->dataHandle($user->getWithNewToken(),"user");
        }
        $password = new class{};
        $password->password = ["the password is not valid"];
        return MyApp::Json()->errorHandle("Validation",$password);
    }

    public function Logout()
    {
        $user = auth()->user();
        $user->currentAccessToken()->delete();
        return MyApp::Json()->dataHandle("Successfully logged out","message");
    }
}
