<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateGetExamRecordsProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop the procedure if it already exists
        DB::unprepared('DROP PROCEDURE IF EXISTS GetExamRecords');

        // Create the GetExamRecords procedure
        DB::unprepared('
            CREATE PROCEDURE GetExamRecords()
            BEGIN
                SELECT exam.*, users.name AS created_name
                FROM exam
                JOIN users ON users.id = exam.created_by
                WHERE exam.is_delete = 0
                ORDER BY exam.id DESC;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the GetExamRecords procedure if it exists
        DB::unprepared('DROP PROCEDURE IF EXISTS GetExamRecords');
    }
}
