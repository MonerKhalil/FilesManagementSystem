<?php

namespace App\MyApplication\Services\Search;

use App\Models\Group;
use App\MyApplication\MyApp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchGroup implements ISearch
{
    public function getSearch(Request $request,string $name=null, bool $isPageniate = false): JsonResponse
    {
        $groups = is_null($name) ? Group::query() : Group::query()->where("name","like","%".strtolower($name)."%");
        if ($isPageniate){
            return MyApp::Json()->Paginate("groups",$groups->paginate(MyApp::Json()->countItems($request)));
        }
        return MyApp::Json()->dataHandle($groups->get(),"groups");
    }
}
