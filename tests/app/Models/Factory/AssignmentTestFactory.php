<?php

namespace Tests\app\Models\Factory;

use App\Models\Assignment;
use Faker\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AssignmentTestFactory extends Factory
{
    public static function createForTest(
        bool $save = false,
        string $departmentId = null,
        string $patientId = null
    ): Assignment {
        $departmentId = $departmentId ?? DepartmentTestFactory::createForTest($save)->id;
        $patientId = $patientId ?? PatientTestFactory::createForTest($save)->id;

        $assignment = new Assignment(
            [
                'id'              => Str::orderedUuid(),
                'department_id'   => (string)$departmentId,
                'patient_id'      => (string)$patientId,
                'created_at'      => Carbon::now()->format('Y-m-d H:i:s')
            ]
        );

        if ($save) {
            $assignment->save();
        }

        return $assignment;
    }
}
