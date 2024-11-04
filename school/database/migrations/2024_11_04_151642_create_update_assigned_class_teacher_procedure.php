<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUpdateAssignedClassTeacherProcedure extends Migration
{
    /**
     * Run the migrations.
     * 
     * @return void
     */
    public function up(): void
    {
        DB::unprepared('
            DROP PROCEDURE IF EXISTS UpdateAssignedClassTeacher;

            CREATE PROCEDURE UpdateAssignedClassTeacher(
                IN p_class_id INT,
                IN p_teacher_id INT,
                IN p_new_status INT,
                IN p_updated_by INT
            )
            BEGIN
                -- Update existing record if teacher is already assigned
                IF EXISTS (
                    SELECT 1 FROM assign_class_teacher
                    WHERE class_id = p_class_id
                    AND teacher_id = p_teacher_id
                    AND is_delete = 0
                ) THEN
                    UPDATE assign_class_teacher
                    SET status = p_new_status,
                        updated_by = p_updated_by,
                        updated_at = NOW()
                    WHERE class_id = p_class_id
                    AND teacher_id = p_teacher_id
                    AND is_delete = 0;
                END IF;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     * 
     * @return void
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS UpdateAssignedClassTeacher');
    }
}
