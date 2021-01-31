<?php

namespace App\Exceptions;

use Exception;

class DepartmentNotExistingException extends Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
