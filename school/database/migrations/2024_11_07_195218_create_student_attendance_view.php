<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW student_attendance_view AS
            SELECT 
                student_attendance.*,
                class.name AS class_name,
                student.name AS student_name,
                student.last_name AS student_last_name,
                createdby.name AS created_name
            FROM 
                student_attendance
            JOIN 
                class ON class.id = student_attendance.class_id
            JOIN 
                student ON student.id = student_attendance.student_id
            JOIN 
                users AS createdby ON createdby.id = student_attendance.created_by
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS student_attendance_view");
    }
};
