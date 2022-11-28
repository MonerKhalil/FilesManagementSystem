<?php

namespace App\MyApplication;

class MyApp
{
    private static ?MyApp $app = null;

    private static ?UploadFile $uploadFile = null;
    private static ?Json $json = null;

    private function __construct()
    {

    }

    public static function getApp(): MyApp
    {
        if(is_null(self::$app)){
            self::$app = new MyApp();
        }
        return self::$app;
    }

    public static function Json(): Json
    {
        if(is_null(self::$json)){
            self::$json = new Json();
        }
        return self::$json;
    }
    public static function uploadFile(): UploadFile
    {
        if(is_null(self::$uploadFile)){
            self::$uploadFile = new UploadFile();
        }
        return self::$uploadFile;
    }

}
