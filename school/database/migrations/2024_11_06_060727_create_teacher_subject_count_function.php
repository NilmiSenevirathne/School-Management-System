<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTeacherSubjectCountFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS getTeacherSubjectCount');

        DB::unprepared('
            CREATE FUNCTION getTeacherSubjectCount(teacher_id INT) 
            RETURNS INT DETERMINISTIC
            BEGIN
                DECLARE subject_count INT;
                SELECT COUNT(DISTINCT subject.id) INTO subject_count
                FROM assign_class_teacher
                JOIN class ON class.id = assign_class_teacher.class_id
                JOIN class_subject ON class_subject.class_id = class.id
                JOIN subject ON subject.id = class_subject.subject_id
                WHERE assign_class_teacher.teacher_id = teacher_id
                AND assign_class_teacher.is_delete = 0
                AND assign_class_teacher.status = 0
                AND class_subject.is_delete = 0
                AND class_subject.status = 0
                AND subject.is_delete = 0
                AND subject.status = 0;
                RETURN subject_count;
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
        DB::unprepared('DROP FUNCTION IF EXISTS getTeacherSubjectCount');
    }
}
