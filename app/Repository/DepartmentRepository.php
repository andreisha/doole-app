<?php

namespace App\Repository;

use App\Models\Department;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepartmentRepository extends AbstractRepository
{
    public const DEPARTMENTS_TABLE_NAME = 'departments';

    public function __construct(Department $model)
    {
        parent::__construct($model, self::DEPARTMENTS_TABLE_NAME);
    }

    public function create(string $name): Department
    {
        $department = new Department();
        $department->id = Str::orderedUuid();
        $department->name = $name;
        $department->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $department->save();

        return $department;
    }


    public function edit(string $id, string $name): void
    {
        DB::table(self::DEPARTMENTS_TABLE_NAME)
            ->where('id', $id)
            ->update([ 'name' => $name]);
    }
}
