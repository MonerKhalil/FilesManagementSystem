<?php

namespace App\MyApplication\Services\Search;

use App\Models\File;
use App\MyApplication\MyApp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class SearchFile implements ISearch
{
    public function getSearch(Request $request,string $name = null, bool $isPageniate = false):JsonResponse
    {
        $files = is_null($name) ? File::query() : File::query()->where("name","like","%".strtolower($name)."%");
        if ($isPageniate){
            return MyApp::Json()->Paginate("files",$files->paginate(MyApp::Json()->countItems($request)));
        }
        return MyApp::Json()->dataHandle($files->get(),"files");
    }
}
