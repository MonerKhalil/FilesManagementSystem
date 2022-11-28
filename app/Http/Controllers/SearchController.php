<?php

namespace App\Http\Controllers;

use App\MyApplication\Services\Search\SearchFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SearchController extends Controller
{
    private SearchFactory $searchFactory;
    public function __construct()
    {
        $this->searchFactory = new SearchFactory();
    }

    public function Search(Request $request,$type): JsonResponse
    {
        $request->validate(["name" => ["nullable","string"],"paginate" => ["nullable","boolean"]]);
        $paginate = ($request->has("paginate") && is_bool($request->paginate)) ? $request->paginate : false;
        return match ($type) {
            "file" => $this->searchFactory
                ->createSearchFile()->getDate($request, $request->name,$paginate),
            "user" => $this->searchFactory
                ->createSearchUser()->getDate($request, $request->name,$paginate),
            "group" => $this->searchFactory
                ->createSearchGroup()->getDate($request, $request->name,$paginate),
            default => throw new NotFoundHttpException(""),
        };
    }

}
