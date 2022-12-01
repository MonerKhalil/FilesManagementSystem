<?php

namespace App\MyApplication\Services\Search;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SearchFactory extends AbstractSearchFactory
{
    protected function createSearchFile():ISearch{
        return new SearchFile();
    }
    protected function createSearchUser():ISearch{
        return new SearchUser();
    }
    protected function createSearchGroup():ISearch{
        return new SearchGroup();
    }

    public function getDate(Request $request,$type,?string $name = null): JsonResponse
    {
        $paginate = ($request->has("paginate") && is_bool($request->paginate)) ? $request->paginate : false;
        return match ($type) {
            "file"  => $this->createSearchFile()->getSearch($request,$name,$paginate),
            "user"  => $this->createSearchUser()->getSearch($request,$name,$paginate),
            "group" => $this->createSearchGroup()->getSearch($request,$name,$paginate),
            default => throw new NotFoundHttpException(""),
        };
    }

}
