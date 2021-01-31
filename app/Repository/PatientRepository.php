<?php

namespace App\Repository;

use App\Models\Patient;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PatientRepository extends AbstractRepository
{
    public const PATIENTS_TABLE_NAME = 'patients';

    public function __construct(Patient $model)
    {
        parent::__construct($model, self::PATIENTS_TABLE_NAME);
    }

    public function create(string $firstName, string $lastName): Patient
    {
        $patient = new Patient();
        $patient->id = Str::orderedUuid();
        $patient->firstName = $firstName;
        $patient->lastName = $lastName;
        $patient->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $patient->save();

        return $patient;
    }

    public function edit(string $id, string $firstName, string $lastName): void
    {
        DB::table(self::PATIENTS_TABLE_NAME)
            ->where('id', $id)
            ->update([ 'firstName' => $firstName, 'lastName' => $lastName]);
    }
}
