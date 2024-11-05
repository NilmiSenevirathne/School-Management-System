<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTeacherFetchMyClassAndSubjectProcedure extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE PROCEDURE TeacherFetchMyClassAndSubject(IN teacher_id INT)
            BEGIN
                SELECT 
                    act.*, 
                    c.name AS class_name,
                    s.name AS subject_name,
                    s.type AS subject_type
                FROM assign_class_teacher AS act
                INNER JOIN class AS c ON c.id = act.class_id
                INNER JOIN class_subject AS cs ON cs.class_id = c.id
                INNER JOIN subject AS s ON s.id = cs.subject_id
                WHERE act.teacher_id = teacher_id
                  AND act.is_delete = 0
                  AND act.status = 0
                  AND s.status = 0
                  AND s.is_delete = 0
                  AND cs.status = 0
                  AND cs.is_delete = 0;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS TeacherFetchMyClassAndSubject');
    }
}
