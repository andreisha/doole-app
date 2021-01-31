<?php

namespace App\Http\Controllers;

use App\Exceptions\DepartmentNotExistingException;
use App\Exceptions\PatientNotExistingException;
use App\Repository\AssignmentRepository;
use App\Repository\DepartmentRepository;
use App\Repository\PatientRepository;
use Ramsey\Uuid\Uuid;

class AssignmentController extends Controller
{
    private AssignmentRepository $assignmentRepository;
    private DepartmentRepository $departmentRepository;
    private PatientRepository $patientRepository;

    public function __construct(
        AssignmentRepository $assignmentRepository,
        DepartmentRepository $departmentRepository,
        PatientRepository $patientRepository
    ) {
        $this->assignmentRepository = $assignmentRepository;
        $this->departmentRepository = $departmentRepository;
        $this->patientRepository = $patientRepository;
    }

    /**
     * Display the patients assigned to a department
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function showPatientsForDepartment(string $departmentId)
    {
        $assignmentsForDepartment = $this->assignmentRepository->findPatientsWhereDepartmentIsAssigned($departmentId);
        $patientsAndCreatedAt = array();

        if (!$assignmentsForDepartment->isEmpty()) {
            $name = $assignmentsForDepartment->first()->name;
            foreach ($assignmentsForDepartment as $assignment) {
                array_push(
                    $patientsAndCreatedAt,
                    [
                        'firstName'    => $assignment->firstName,
                        'lastName'     => $assignment->lastName,
                        'created_at'   => $assignment->created_at,
                    ]
                );
            }
        } else {
            if ($department = $this->departmentRepository->find($departmentId)){
                $name = $department->name;
            } else {
                throw new DepartmentNotExistingException();
            }
        }

        return view(
            'departments.department_details',
            [
                'departmentId'         => $departmentId,
                'name'                 => $name,
                'patientsAndCreatedAt' => $patientsAndCreatedAt
            ]
        );
    }

    /**
     * List possible departments for a specific patient
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     * @throws DepartmentNotExistingException
     */
    public function listPossiblePatientsForDepartment(string $departmentId)
    {
        if ($department = $this->departmentRepository->find($departmentId)) {
            $name = $department->name;
        } else {
            throw new DepartmentNotExistingException();
        }

        return view(
            'assignments.list_patients_possible_for_department',
            [
                'departmentId'     => $departmentId,
                'name'             => $name,
                'allPatients'      => $this->assignmentRepository->listPossiblePatientsForDepartment($departmentId)
            ]
        );
    }

    /**
     * Assign selected departments to patient
     **
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     * @throws PatientNotExistingException
     */
    public function assignPatientsToDepartment(string $departmentId)
    {
        $patientsId = $_POST['patients'];

        foreach ($patientsId as $patientId){
            if (Uuid::isValid($departmentId) && Uuid::isValid($patientId)) {
                $this->assignmentRepository->create($departmentId, $patientId);
            }
        }

        return redirect('/departments/' . $departmentId );
    }

    /**
     * Display the departments where a patient is assigned
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     * @throws PatientNotExistingException
     */
    public function showDepartmentsForPatient(string $patientId)
    {
        $assignmentsForPatient = $this->assignmentRepository->findDepartmentsWherePatientIsAssigned($patientId);
        $departmentsAndCreatedAt = array();

        if (!$assignmentsForPatient->isEmpty()) {
            $firstName = $assignmentsForPatient->first()->firstName;
            $lastName = $assignmentsForPatient->first()->lastName;
            foreach ($assignmentsForPatient as $assignment) {
                array_push(
                    $departmentsAndCreatedAt,
                    [
                        'name'       => $assignment->name,
                        'created_at' => $assignment->created_at
                    ]
                );
            }
        } else {
            if ($patient = $this->patientRepository->find($patientId)) {
                $firstName = $patient->firstName;
                $lastName = $patient->lastName;
            } else {
                throw new PatientNotExistingException();
            }
        }

        return view(
            'patients.patient_details',
            [
                'patientId'                => $patientId,
                'firstName'                => $firstName,
                'lastName'                 => $lastName,
                'departmentsAndCreatedAt'  => $departmentsAndCreatedAt
            ]
        );
    }

    /**
     * List possible departments for a specific patient
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function listPossibleDepartmentsForPatient(string $patientId)
    {
        if ($patient = $this->patientRepository->find($patientId)) {
            $firstName = $patient->firstName;
            $lastName = $patient->lastName;
        } else {
            throw new PatientNotExistingException();
        }

        return view(
            'assignments.list_departments_possible_for_patient',
            [
                'patientId'                => $patientId,
                'firstName'                => $firstName,
                'lastName'                 => $lastName,
                'allDepartments'           => $this->assignmentRepository->listPossibleDepartmentsForPatient($patientId)
            ]
        );
    }

    /**
     * Assign selected departments to patient
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     * @throws PatientNotExistingException
     */
    public function assignDepartmentsToPatient(string $patientId)
    {
        $departmentsId = $_POST['departments'];
        foreach ($departmentsId as $departmentId){
            if (Uuid::isValid($departmentId) && Uuid::isValid($patientId)) {
                $this->assignmentRepository->create($departmentId, $patientId);
            }
        }

        return redirect('/patients/' . $patientId );
    }
}
