<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Exceptions\DepartmentNotExistingException;
use App\Exceptions\PatientNotExistingException;
use App\Http\Controllers\AssignmentController;
use App\Repository\AssignmentRepository;
use App\Repository\DepartmentRepository;
use App\Repository\PatientRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\app\Models\Factory\AssignmentTestFactory;
use Tests\app\Models\Factory\DepartmentTestFactory;
use Tests\app\Models\Factory\PatientTestFactory;
use Tests\TestCase;

class AssignmentControllerTest extends TestCase
{
    use RefreshDatabase;

    private AssignmentRepository $assignmentRepository;
    private DepartmentRepository $departmentRepository;
    private PatientRepository $patientRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->createApplication();
        $this->assignmentRepository = $this->app->make(AssignmentRepository::class);
        $this->departmentRepository = $this->app->make(DepartmentRepository::class);
        $this->patientRepository = $this->app->make(PatientRepository::class);
        $this->refreshDatabase();
    }

    /**
     * @dataProvider providerTestShowPatientsForDepartment
     */
    public function testShowPatientsForDepartment(bool $hasDepartmentPatients)
    {
        $department = DepartmentTestFactory::createForTest(true);
        $departmentId = (string)$department->id;
        $departmentsAndCreatedAt = array();
        if ($hasDepartmentPatients) {
            AssignmentTestFactory::createForTest(true, $departmentId);
            $assignmentsForDepartment = $this->assignmentRepository->findPatientsWhereDepartmentIsAssigned($departmentId);
            foreach ($assignmentsForDepartment as $assignment) {
                array_push(
                    $departmentsAndCreatedAt,
                    [
                        'firstName'    => $assignment->firstName,
                        'lastName'     => $assignment->lastName,
                        'created_at'   => $assignment->created_at,
                    ]
                );
            }
        }

        $response = $this->get('/departments/' . $departmentId);

        $response->assertStatus(200);
        $response->assertViewIs('departments.department_details');
        $response->assertViewHas(
            [
                'departmentId'         => $departmentId,
                'name'                 => $department->name,
                'patientsAndCreatedAt' => $departmentsAndCreatedAt
            ]
        );
    }

    public function providerTestShowPatientsForDepartment(): iterable
    {
        yield 'Department with patient' => [
            true
        ];
        yield 'Department without patient' => [
            false
        ];
    }

    public function testListPossiblePatientsForDepartment()
    {
        $assignment = AssignmentTestFactory::createForTest(true);
        $departmentId = (string)$assignment->department_id;
        $name = ($department = $this->departmentRepository->find($departmentId))->name;

        $response = $this->get('/departments/' . $departmentId . '/assignPatients');

        $response->assertStatus(200);
        $response->assertViewIs('assignments.list_patients_possible_for_department');
        $response->assertViewHas(
            [
                'departmentId' => $departmentId,
                'name' => $name,
                'allPatients' => $this->assignmentRepository->listPossiblePatientsForDepartment($departmentId)
            ]
        );
    }

    public function testListPossiblePatientsForDepartmentThrowsExceptionIfDepartmentDoesntExist()
    {
        $assignmentController = new AssignmentController(
            $this->assignmentRepository,
            $this->departmentRepository,
            $this->patientRepository
        );

        $this->expectException(DepartmentNotExistingException::class);

        $assignmentController->listPossiblePatientsForDepartment('fakeId');
    }

    /**
     * @dataProvider providerTestShowDepartmentsForPatient
     */
    public function testShowDepartmentsForPatient(bool $hasPatientDepartments)
    {
        $patient = PatientTestFactory::createForTest(true);
        $patientId = (string)$patient->id;
        $departmentsAndCreatedAt = array();
        if ($hasPatientDepartments) {
            AssignmentTestFactory::createForTest(true, null, $patientId);
            $assignmentsForPatient = $this->assignmentRepository->findDepartmentsWherePatientIsAssigned($patientId);
            foreach ($assignmentsForPatient as $assignment) {
                array_push(
                    $departmentsAndCreatedAt,
                    [
                        'name' => $assignment->name,
                        'created_at' => $assignment->created_at
                    ]
                );
            }
        }

        $response = $this->get('/patients/' . $patientId);

        $response->assertStatus(200);
        $response->assertViewIs('patients.patient_details');
        $response->assertViewHas(
            [
                'patientId' => (string)$patient->id,
                'firstName' => $patient->firstName,
                'lastName' => $patient->lastName,
                'departmentsAndCreatedAt' => $departmentsAndCreatedAt
            ]
        );
    }

    public function providerTestShowDepartmentsForPatient(): iterable
    {
        yield 'Patient with department' => [
            true
        ];
        yield 'Patient without department' => [
            false
        ];
    }

    public function testListPossibleDepartmentsForPatient()
    {
        $assignment = AssignmentTestFactory::createForTest(true);
        $patientId = (string)$assignment->patient_id;
        $patient = $this->patientRepository->find($patientId);
        $firstName = $patient->firstName;
        $lastName = $patient->lastName;

        $response = $this->get('/patients/' . $patientId . '/assignDepartments');

        $response->assertStatus(200);
        $response->assertViewIs('assignments.list_departments_possible_for_patient');
        $response->assertViewHas(
            [
                'patientId' => $patientId,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'allDepartments' => $this->assignmentRepository->listPossibleDepartmentsForPatient($patientId)
            ]
        );
    }

    public function testListPossibleDepartmentsForPatientThrowsExceptionIfPatientDoesntExist()
    {
        $assignmentController = new AssignmentController(
            $this->assignmentRepository,
            $this->departmentRepository,
            $this->patientRepository
        );

        $this->expectException(PatientNotExistingException::class);

        $assignmentController->listPossibleDepartmentsForPatient('fakeId');
    }
}
