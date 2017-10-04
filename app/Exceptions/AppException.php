<?php
namespace App\Exceptions;

class AppException extends \Exception
{
    protected $errorCode;

    public function __construct($errorCode = "", $message = "Data Error", $code = 0, Exception $previous = null)
    {
        $this->errorCode = $errorCode;
        parent::__construct($message, $code, $previous);
    }
    
    public function getErrorCode()
    {
        return $this->errorCode;
    }
}