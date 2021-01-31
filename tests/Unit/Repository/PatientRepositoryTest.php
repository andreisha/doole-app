<?php

namespace Tests\Unit\Repository;

use App\Repository\PatientRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;
use Tests\app\Models\Factory\PatientTestFactory;
use Tests\TestCase;

class PatientRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private PatientRepository $patientRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->createApplication();

        $this->patientRepository = $this->app->make(PatientRepository::class);
        $this->refreshDatabase();
    }

    public function testCreate()
    {
        $patient = $this->patientRepository->create(
            PatientTestFactory::FIRST_NAME_TEST,
            PatientTestFactory::LAST_NAME_TEST
        );

        $this->assertTrue(Uuid::isValid($patient->id));
        $this->assertSame(PatientTestFactory::FIRST_NAME_TEST, $patient->firstName);
        $this->assertSame(PatientTestFactory::LAST_NAME_TEST, $patient->lastName);
    }


    public function testEdit(): void
    {
        $patient = PatientTestFactory::createForTest(true);
        $newFirstName = 'newFirstName';
        $newLastName = 'newLastName';

        $this->patientRepository->edit($patient->id, $newFirstName, $newLastName);

        $patient->refresh();

        $this->assertSame($newFirstName, $patient->firstName);
        $this->assertSame($newLastName, $patient->lastName);
    }
}
