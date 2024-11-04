<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateInsertAssignedClassTeacherProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            DROP PROCEDURE IF EXISTS InsertAssignedClassTeacher;

            CREATE PROCEDURE InsertAssignedClassTeacher(
                IN classID INT,
                IN teacherID INT,
                IN assignStatus INT,
                IN createdBy INT
            )
            BEGIN
                DECLARE existingID INT;

                -- Check if the assignment already exists
                SELECT id INTO existingID 
                FROM assign_class_teacher 
                WHERE class_id = classID AND teacher_id = teacherID 
                LIMIT 1;

                -- If an existing assignment is found, update its status
                IF existingID IS NOT NULL THEN
                    UPDATE assign_class_teacher 
                    SET status = assignStatus, updated_by = createdBy, updated_at = NOW() 
                    WHERE id = existingID;
                ELSE
                    -- If no assignment is found, insert a new record
                    INSERT INTO assign_class_teacher (class_id, teacher_id, status, created_by, created_at, is_delete)
                    VALUES (classID, teacherID, assignStatus, createdBy, NOW(), 0);
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
        DB::unprepared('DROP PROCEDURE IF EXISTS InsertAssignedClassTeacher');
    }
}
