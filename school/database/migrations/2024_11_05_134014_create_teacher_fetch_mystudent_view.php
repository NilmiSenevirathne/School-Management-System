<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTeacherFetchMyStudentView extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('
        CREATE VIEW teacher_fetch_mystudent_view AS
        SELECT student.*, class.name AS class_name
        FROM student
        LEFT JOIN class ON class.id = student.class_id
        LEFT JOIN assign_class_teacher ON assign_class_teacher.class_id = class.id
        WHERE student.is_delete = 0
    ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_fetch_mystudent_view');
    }
};
