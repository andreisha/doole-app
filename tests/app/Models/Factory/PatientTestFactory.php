<?php

namespace Tests\app\Models\Factory;

use App\Models\Patient;
use Faker\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PatientTestFactory extends Factory
{
    public const FIRST_NAME_TEST = 'typicalFirstName';
    public const LAST_NAME_TEST = 'typicalLastName';

    public static function createForTest(
        bool $save = false,
        string $firstName = null,
        string $lastName = null
    ): Patient {
        $patient = new Patient(
            [
                'id'        => Str::orderedUuid(),
                'firstName' => $firstName ?? self::FIRST_NAME_TEST,
                'lastName'  => $lastName ?? self::LAST_NAME_TEST,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        );

        if ($save) {
            $patient->save();
        }

        return $patient;
    }
}
