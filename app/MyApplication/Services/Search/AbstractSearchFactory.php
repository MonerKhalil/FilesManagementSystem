<?php

namespace App\MyApplication\Services\Search;

use Illuminate\Http\Request;

abstract class AbstractSearchFactory
{
    protected abstract function createSearchFile():ISearch;
    protected abstract function createSearchUser():ISearch;
    protected abstract function createSearchGroup():ISearch;
    public abstract function getDate(Request $request,$type,?string $name = null):mixed;
}
