<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateInsertExamProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE PROCEDURE InsertExam(IN exam_name VARCHAR(255), IN exam_note TEXT, IN created_by_id INT)
            BEGIN
                INSERT INTO exam (name, note, created_by, created_at)
                VALUES (exam_name, exam_note, created_by_id, NOW());
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
        DB::unprepared('DROP PROCEDURE IF EXISTS InsertExam');
    }
}
