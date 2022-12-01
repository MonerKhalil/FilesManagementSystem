<?php

namespace App\Http\Controllers;

use App\MyApplication\Services\Search\AbstractSearchFactory;
use App\MyApplication\Services\Search\SearchFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private AbstractSearchFactory $searchFactory;
    public function __construct()
    {
        $this->searchFactory = new SearchFactory();
    }

    public function Search(Request $request,$type): JsonResponse
    {
        $request->validate(["name" => ["nullable","string"],"paginate" => ["nullable","boolean"]]);
        return $this->searchFactory->getDate($request,$type,$request->name);
    }

}
