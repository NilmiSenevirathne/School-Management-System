<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAddTeacherProcedure extends Migration
{
    public function up()
    {
        DB::unprepared('
            DROP PROCEDURE IF EXISTS add_teacher_procedure;

            CREATE PROCEDURE add_teacher_procedure(IN teacher_email VARCHAR(255))
            BEGIN
                DECLARE teacher_id INT;
                DECLARE class_id INT;
                
                -- Fetch teacher details by email
                SELECT id INTO teacher_id FROM teacher WHERE email = teacher_email LIMIT 1;

                -- If teacher is found, fetch the class details
                IF teacher_id IS NOT NULL THEN
                    SELECT assign_class_teacher.class_id, class.name AS class_name, subject.name AS subject_name
                    FROM assign_class_teacher
                    JOIN class ON class.id = assign_class_teacher.class_id
                    JOIN class_subject ON class_subject.class_id = class.id
                    JOIN subject ON subject.id = class_subject.subject_id
                    WHERE assign_class_teacher.teacher_id = teacher_id AND assign_class_teacher.is_delete = 0;
                    -- If teacher not found, raise an error
                END IF;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS add_teacher_procedure');
    }
}
