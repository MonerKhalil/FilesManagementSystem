<?php

namespace App\MyApplication;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class Json
{
    /**
     * @param Request $request
     * @return int
     */
    public function countItems(Request $request): int
    {
        return ($request->has("num_items") &&
            is_numeric($request->num_items) && ($request->num_items > 0))
            ? (int)$request->num_items : 10;
    }

    /**
     * @param string $name
     * @param mixed $paginate
     * @return JsonResponse
     */
    public function Paginate(string $name,Paginator $paginate): JsonResponse
    {
        $data = [
            $name => $paginate->items(),
            "current_page" => $paginate->currentPage(),
            "url_next_page" => $paginate->nextPageUrl(),
            "url_first_page" => $paginate->path()."?page=1",
            "url_last_page" => $paginate->path()."?page=".$paginate->lastPage(),
            "total_pages" => $paginate->lastPage(),
            "total_items" => $paginate->total()
        ];
        return $this->dataHandle($data,"paginate");
    }

    /**
     * @param mixed $data
     * @param string|null $name
     * @return JsonResponse
     */
    public function dataHandle(mixed $data , string $name = null): JsonResponse
    {
        return !is_null($name) ? response()->json(['data'=>[$name => $data]]) : response()->json(["data"=>$data]);
    }

    /**
     * @param string $name
     * @param mixed $messageError
     * @param $code
     * @return JsonResponse
     */
    public function errorHandle(string $name, mixed $messageError,$code = 400): JsonResponse
    {
        $code = 400;
        return response()->json([
            "errors" => [
                $name => $messageError
            ]
        ],$code);
    }
}
