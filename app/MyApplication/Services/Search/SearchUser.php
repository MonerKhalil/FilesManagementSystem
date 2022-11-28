<?php

namespace App\MyApplication\Services\Search;

use App\Models\User;
use App\MyApplication\MyApp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchUser implements ISearch
{

    public function getSearch(Request $request,string $name=null, bool $isPageniate = false):JsonResponse
    {
        $users = is_null($name) ?  User::query() : User::query()->where("name","like","%".$name."%");
        if ($isPageniate){
            return MyApp::Json()->Paginate("users",$users->paginate(MyApp::Json()->countItems($request)));
        }
        return MyApp::Json()->dataHandle($users->get(),"users");
    }
}
