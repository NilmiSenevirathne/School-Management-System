<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateExamSubjectView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DROP VIEW IF EXISTS exam_subject_view');

        DB::statement('
            CREATE VIEW exam_subject_view AS
            SELECT 
                marks_register.*,
                exam.name AS exam_name,
                subject.name AS subject_name
            FROM 
                marks_register
            JOIN 
                exam ON exam.id = marks_register.exam_id
            JOIN 
                subject ON subject.id = marks_register.subject_id
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS exam_subject_view');
    }
}