<?php

namespace App\MyApplication\Services\Search;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchFactory
{
    private ?ISearch $search = null;

    public function createSearchFile(){
        $this->search = new SearchFile();
        return $this;
    }
    public function createSearchUser(){
        $this->search = new SearchUser();
        return $this;
    }
    public function createSearchGroup(){
        $this->search = new SearchGroup();
        return $this;
    }

    public function getDate(Request $request,?string $name = null,bool $isPageniate = false): JsonResponse
    {
        if (is_null($this->search)){
            $this->search = new SearchGroup();
        }
        return $this->search->getSearch($request,$name,$isPageniate);
    }

}
