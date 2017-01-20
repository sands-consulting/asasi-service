<?php

namespace Sands\Asasi\Exceptions;

use Exception;

class ServiceException extends Exception 
{

    private $context;

    public function getContext()
    {
        return $this->context;
    }

    public function __construct($operation, $context = [], $code = 0, Exception $previous = null)
    {
        $this->context = $context;
        parent::__construct('Error on service operation: ' . $operation, $code, $previous);
    }
}
