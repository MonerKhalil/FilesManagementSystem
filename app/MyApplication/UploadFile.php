<?php

namespace App\MyApplication;


class UploadFile
{
    private array $disks;
    private ?string $fileName;
    private const DEFAULT_DISK = "files";

    public function __construct()
    {
        $this->disks = [
            "files" => "Uploads/files",
            "images" => "Uploads/images"
        ];
        $this->fileName = null;
    }

    /**
     * @param $file
     * @param string|null $diskType
     * @param string $dir
     * @return string
     */
    public function upload($file, string $diskType = null, string $dir = ""):string
    {
        $TempName = time().$file->getClientOriginalName();
        if(!is_null($diskType) && array_key_exists($diskType,$this->disks)){
            $TempName =  $file->storeAs($dir,$TempName,[
                "disk" => $diskType
            ]);
            $TempName = $this->disks[$diskType] ."/".$TempName;
        }else{
            $TempName = $file->storeAs($dir,$TempName,[
                "disk" => self::DEFAULT_DISK
            ]);
            $TempName = $this->disks[self::DEFAULT_DISK] ."/".$TempName;
        }
        $this->fileName = $TempName;
        return $TempName;
    }

    public function clearFile(){
        $this->fileName = null;
    }

    public function deleteFile(string $path): bool
    {
        if (file_exists(public_path($path))){
            unlink(public_path($path));
            return true;
        }
        return false;
    }
    public function rollBackUpload(){
        if (!is_null($this->fileName)){
            $this->DeleteFile($this->fileName);
        }
        $this->fileName = null;
    }

}
