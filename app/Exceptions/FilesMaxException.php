<?php

namespace App\Exceptions;

use Exception;

class FilesMaxException extends Exception
{
    protected $code = 400;
    protected $message = "You cannot upload more files because you have exceeded the allowed number";
}
