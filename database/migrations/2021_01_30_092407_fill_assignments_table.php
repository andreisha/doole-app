<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FillAssignmentsTable extends Migration
{
    private string $assignmentsTable = 'assignments';
    private int $numberOfRoles = 5;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $departments = \App\Models\Department::all();
        $patients = \App\Models\Patient::all();
        for ($roleNumber = 0; $roleNumber < $this->numberOfRoles; $roleNumber++) {
            DB::table($this->assignmentsTable)->insert(
                array(
                    'id' => Str::orderedUuid(),
                    'department_id' => $departments[$roleNumber]->id,
                    'patient_id' => $patients[$roleNumber]->id,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                )
            );

            // We'll have some people having 2 departments
            if ($roleNumber%2) {
                DB::table($this->assignmentsTable)->insert(
                    array(
                        'id' => Str::orderedUuid(),
                        'department_id' => $departments[$roleNumber-1]->id,
                        'patient_id' => $patients[$roleNumber]->id,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                    )
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table($this->assignmentsTable)->truncate();
    }
}
