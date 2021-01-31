<?php

namespace Tests\Feature\Exceptions;

use App\Exceptions\DepartmentNotExistingException;
use App\Exceptions\Handler;
use App\Exceptions\PatientNotExistingException;
use Illuminate\Http\Request;
use Tests\TestCase;

class HandlerTest extends TestCase
{
    public function testRenderWhenPatientNotExistingException()
    {
        parent::setUp();
        $this->createApplication();

        $response = $this->app->make(Handler::class)->render(new Request(), new PatientNotExistingException());

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(view('errors.patient_not_existing'), $response->getContent());
    }

    public function testRenderWhenDepartmentNotExistingException()
    {
        parent::setUp();
        $this->createApplication();

        $response = $this->app->make(Handler::class)->render(new Request(), new DepartmentNotExistingException());

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(view('errors.department_not_existing'), $response->getContent());
    }
}
