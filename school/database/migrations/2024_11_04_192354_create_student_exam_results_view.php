<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateStudentExamResultsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE VIEW student_exam_results AS
            SELECT 
                marks_register.*,
                exam.name AS exam_name
            FROM 
                marks_register
            JOIN 
                exam ON exam.id = marks_register.exam_id
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS student_exam_results');
    }
}