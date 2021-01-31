<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [];


   /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
     public function render($request, Throwable $exception)
    {
       if ($exception instanceof DepartmentNotExistingException) {
            return response()->view('errors.department_not_existing', [], 500);
        } elseif ($exception instanceof PatientNotExistingException) {
            return response()->view('errors.patient_not_existing', [], 500);
        }

       return response()->view('errors.errors', [], 500);
    }
}
