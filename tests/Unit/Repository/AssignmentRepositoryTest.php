<?php

namespace Tests\Unit\Repository;

use App\Repository\AssignmentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;
use Tests\app\Models\Factory\AssignmentTestFactory;
use Tests\app\Models\Factory\DepartmentTestFactory;
use Tests\app\Models\Factory\PatientTestFactory;
use Tests\TestCase;

class AssignmentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private AssignmentRepository $assignmentRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->createApplication();
dddddd
        $this->assignmentRepository = $this->app->make(AssignmentRepository::class);
        $this->refreshDatabase();
    }

    public function testCreate()
    {
        $departmentId = DepartmentTestFactory::createForTest(true)->id;
        $patientId = PatientTestFactory::createForTest(true)->id;
        $assignment = $this->assignmentRepository->create($departmentId, $patientId);

        $this->assertTrue(Uuid::isValid($assignment->id));
        $this->assertSame((string)$departmentId, $assignment->department_id);
        $this->assertSame((string)$patientId, $assignment->patient_id);
    }


    public function testListPossibleDepartmentsForPatient(): void
    {
        // To isolate the test
        $department = DepartmentTestFactory::createForTest(true);
        $patient = PatientTestFactory::createForTest(true);
        AssignmentTestFactory::createForTest(true, $department->id, $patient->id);

        $departmentPossibleForPatient = DepartmentTestFactory::createForTest(true);

        $possibleDepartmentsForPatient = $this->assignmentRepository->listPossibleDepartmentsForPatient($patient->id);

        $this->assertEquals(
            $departmentPossibleForPatient->id,
            array_slice($possibleDepartmentsForPatient->toArray(), -1)[0]->id
        );

        // Populate the db again
        $this->refreshDatabase();
    }

    public function testListPossiblePatientsForDepartment(): void
    {
        // To isolate the test
        $department = DepartmentTestFactory::createForTest(true);
        $patient = PatientTestFactory::createForTest(true);
        AssignmentTestFactory::createForTest(true, $department->id, $patient->id);

        $patientPossibleForDepartment = PatientTestFactory::createForTest(true);

        $possiblePatientsForDepartment = $this->assignmentRepository->listPossiblePatientsForDepartment(
            $department->id
        );

        $this->assertEquals(
            $patientPossibleForDepartment->id,
            array_slice($possiblePatientsForDepartment->toArray(), -1)[0]->id
        );

        // Populate the db again
        $this->refreshDatabase();
    }

    public function testFindDepartmentsWherePatientIsAssigned()
    {
        // To isolate the test
        $patient = PatientTestFactory::createForTest(true);
        $department = DepartmentTestFactory::createForTest(true);
        $department2 = DepartmentTestFactory::createForTest(true);
        AssignmentTestFactory::createForTest(true, $department->id, $patient->id);
        AssignmentTestFactory::createForTest(true, $department2->id, $patient->id);

        $departmentsWherePatientIsAssigned = $this->assignmentRepository
            ->findDepartmentsWherePatientIsAssigned($patient->id)->toArray();

        $departments = array();
        foreach ($departmentsWherePatientIsAssigned as $departmentWherePatientIsAssigned) {
            array_push($departments, $departmentWherePatientIsAssigned->name);
        }

        $this->assertEquals([$department->name, $department2->name], $departments);

        // Populate the db again
        $this->refreshDatabase();
    }
}
