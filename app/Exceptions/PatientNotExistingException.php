<?php

namespace App\Exceptions;

use Exception;

class PatientNotExistingException extends Exception
{
    public function __construct()
    {
        parent::__construct();
    }
}
