<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateGetTeacherClassCountFunction extends Migration
{
    public function up()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS getTeacherClassCount');

        DB::unprepared('
            CREATE FUNCTION getTeacherClassCount(teacher_id INT)
            RETURNS INT DETERMINISTIC
            BEGIN
                DECLARE class_count INT;

                SELECT COUNT(DISTINCT assign_class_teacher.class_id)
                INTO class_count
                FROM assign_class_teacher
                JOIN class ON class.id = assign_class_teacher.class_id
                WHERE assign_class_teacher.teacher_id = teacher_id
                AND assign_class_teacher.is_delete = 0
                AND assign_class_teacher.status = 0;
                
                RETURN class_count;
            END;
        ');
    }

    public function down()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS getTeacherClassCount');
    }
}


