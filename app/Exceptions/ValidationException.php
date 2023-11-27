<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    public function __construct($request, array $errors)
    {
        $this->request = $request;
        $this->errors = $errors;
    }

    public function getPath()
    {
        return $this->request->getUri()->getPath();
    }

    public function getOldInput()
    {
        return $this->request->getParsedBody();
    }

    public function getErrors()
    {
        return $this->errors;
    }
}