<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class FillDepartmentsTable extends Migration
{
    private string $departmentsTable = 'departments';
    private int $numberOfDepartments = 5;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        for ($departmentNumber = 0; $departmentNumber < $this->numberOfDepartments; $departmentNumber++) {
            DB::table($this->departmentsTable)->insert(
                array(
                    'id' => Str::orderedUuid(),
                    'name' => 'Department' . $departmentNumber,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                )
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        DB::table($this->departmentsTable)->truncate();
        Schema::enableForeignKeyConstraints();
    }
}
