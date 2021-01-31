<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class FillPatientsTable extends Migration
{
    private string $patientsTable = 'patients';
    private int $numberOfPatients = 5;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        for ($patientNumber = 0; $patientNumber < $this->numberOfPatients; $patientNumber++) {
            DB::table($this->patientsTable)->insert(
                array(
                    'id' => Str::orderedUuid(),
                    'firstName' => 'Maria' . $patientNumber,
                    'lastName' => 'Jimenez',
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
        DB::table($this->patientsTable)->truncate();
        Schema::enableForeignKeyConstraints();
    }
}
