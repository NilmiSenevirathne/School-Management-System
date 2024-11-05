<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddTotalMarksTriggerToMarksRegister extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER calculate_total_marks
            BEFORE INSERT ON marks_register
            FOR EACH ROW
            BEGIN
                SET NEW.total_marks = NEW.home_work + NEW.test_work + NEW.exam;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP TRIGGER IF EXISTS calculate_total_marks");
    }
}
