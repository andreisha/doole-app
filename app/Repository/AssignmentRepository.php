<?php

namespace App\Repository;

use App\Models\Assignment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AssignmentRepository extends AbstractRepository
{
    public const ASSIGNMENTS_TABLE_NAME = 'assignments';

    public function __construct(Assignment $model)
    {
        parent::__construct($model, self::ASSIGNMENTS_TABLE_NAME);
    }

    public function create(string $department_id, string $patient_id): Assignment
    {
        $assignment = new Assignment();
        $assignment->id = Str::orderedUuid();
        $assignment->department_id = $department_id;
        $assignment->patient_id = $patient_id;
        $assignment->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $assignment->save();

        return $assignment;
    }

    public function listPossibleDepartmentsForPatient(string $patientId): Collection
    {
        $assignmentTable = self::ASSIGNMENTS_TABLE_NAME;
        $departmentsTable = DepartmentRepository::DEPARTMENTS_TABLE_NAME;

        $departmentsOfPatientArray = DB::table($assignmentTable)
            ->select($assignmentTable . '.department_id')
            ->where([$assignmentTable . '.patient_id'  => $patientId])
            ->get()
            ->toArray();

        $departmentsOfPatient = array_map(function ($element) {
            return $element->department_id;
        }, $departmentsOfPatientArray);

        return DB::table($departmentsTable)
            ->whereNotIn('id', $departmentsOfPatient)
            ->get();
    }

    public function listPossiblePatientsForDepartment(string $departmentId): Collection
    {
        $assignmentTable = self::ASSIGNMENTS_TABLE_NAME;
        $patientsTable = PatientRepository::PATIENTS_TABLE_NAME;

        $patientsOfDepartmentArray = DB::table($assignmentTable)
            ->select($assignmentTable . '.patient_id')
            ->where([$assignmentTable . '.department_id'  => $departmentId])
            ->get()
            ->toArray();

        $patientsOfDepartment = array_map(function ($element) {
            return $element->patient_id;
        }, $patientsOfDepartmentArray);

        return DB::table($patientsTable)
            ->whereNotIn('id', $patientsOfDepartment)
            ->get();
    }

    public function findDepartmentsWherePatientIsAssigned(string $patientId): Collection
    {
        return $this->executeQuery('patient_id', $patientId);
    }

    public function findPatientsWhereDepartmentIsAssigned(string $departmentId): Collection
    {
        return $this->executeQuery('department_id', $departmentId);
    }

    /**
     * Common method for query using joins with patients and departments tables
     */
    private function executeQuery(string $fieldInAssignmentTable, string $patientXorDepartmentId): Collection
    {
        $assignmentTable = self::ASSIGNMENTS_TABLE_NAME;
        $patientsTable = PatientRepository::PATIENTS_TABLE_NAME;
        $departmentsTable = DepartmentRepository::DEPARTMENTS_TABLE_NAME;

        return DB::table($assignmentTable)
            ->join(
                $patientsTable,
                $patientsTable . '.id',
                '=',
                $assignmentTable . '.patient_id'
            )
            ->join(
                $departmentsTable,
                $departmentsTable . '.id',
                '=',
                $assignmentTable . '.department_id'
            )
            ->select(
                $patientsTable . '.firstName',
                $patientsTable . '.lastName',
                $departmentsTable . '.name',
                $assignmentTable . '.created_at'
            )
            ->where([$assignmentTable . '.' . $fieldInAssignmentTable => $patientXorDepartmentId])
            ->get();
    }
}

