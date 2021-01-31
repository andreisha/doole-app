<?php

namespace Tests\app\Models\Factory;

use App\Models\Department;
use Faker\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DepartmentTestFactory extends Factory
{
    public const NAME_TEST = 'name';

    public static function createForTest(
        bool $save = false,
        string $name = null
    ): Department {
        $department = new Department(
            [
                'id'         => Str::orderedUuid(),
                'name'       => $name ?? self::NAME_TEST,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        );

        if ($save) {
            $department->save();
        }

        return $department;
    }
}
