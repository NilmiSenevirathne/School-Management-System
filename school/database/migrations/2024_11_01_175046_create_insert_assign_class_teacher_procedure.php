<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateInsertAssignClassTeacherProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop the procedure if it already exists
        DB::unprepared('DROP PROCEDURE IF EXISTS AssignClassTeacher');

        // Create the AssignClassTeacher procedure
        DB::unprepared('
            CREATE PROCEDURE AssignClassTeacher(
                IN p_class_id BIGINT,
                IN p_teacher_id BIGINT,
                IN p_created_by BIGINT,
                IN p_status TINYINT
            )
            BEGIN
                DECLARE assignment_exists INT;

                /* Check if an assignment already exists */
                SELECT COUNT(*) INTO assignment_exists
                FROM assign_class_teacher
                WHERE class_id = p_class_id AND teacher_id = p_teacher_id AND is_delete = 0;

                IF assignment_exists > 0 THEN
                    /* Update the existing assignment if it already exists */
                    UPDATE assign_class_teacher
                    SET status = p_status, updated_by = p_created_by, updated_at = NOW()
                    WHERE class_id = p_class_id AND teacher_id = p_teacher_id AND is_delete = 0;
                ELSE
                    /* Insert a new assignment if it doesn’t exist */
                    INSERT INTO assign_class_teacher (class_id, teacher_id, status, created_by, created_at)
                    VALUES (p_class_id, p_teacher_id, p_status, p_created_by, NOW());
                END IF;
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
        // Drop the AssignClassTeacher procedure if it exists
        DB::unprepared('DROP PROCEDURE IF EXISTS AssignClassTeacher');
    }
}
