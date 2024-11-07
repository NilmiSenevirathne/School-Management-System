<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUpdateExamProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE PROCEDURE UpdateExam(IN exam_id INT, IN exam_name VARCHAR(255), IN exam_note TEXT)
            BEGIN
                UPDATE exam
                SET name = exam_name,
                    note = exam_note
                WHERE id = exam_id;
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
        DB::unprepared('DROP PROCEDURE IF EXISTS UpdateExam');
    }
}
