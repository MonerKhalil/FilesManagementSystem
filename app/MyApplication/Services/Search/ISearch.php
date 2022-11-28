<?php

namespace App\MyApplication\Services\Search;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface ISearch
{
    public function getSearch(Request $request,?string $name=null,bool $isPageniate = false): JsonResponse;
}
