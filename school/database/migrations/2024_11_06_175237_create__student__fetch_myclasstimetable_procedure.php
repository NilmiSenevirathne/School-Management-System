<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateStudentFetchMyClassTimetableProcedure extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE PROCEDURE StudentFetchMyClassTimetable(IN student_email VARCHAR(255))
            BEGIN
                DECLARE student_class_id INT;

                -- Fetch the student\'s class_id based on email, with explicit collation for comparison
                SELECT class_id INTO student_class_id
                FROM student
                WHERE email COLLATE utf8mb4_unicode_ci = student_email COLLATE utf8mb4_unicode_ci;

                -- Check if the class_id is found
                IF student_class_id IS NOT NULL THEN
                    -- Fetch the timetable details for the student\'s class
                    SELECT w.id AS week_id, 
                           w.name AS week_name, 
                           s.name AS subject_name, 
                           cst.start_time, 
                           cst.end_time, 
                           cst.room_number
                    FROM class_subject_timetable AS cst
                    JOIN subject AS s ON cst.subject_id = s.id
                    JOIN week AS w ON cst.week_id = w.id
                    WHERE cst.class_id = student_class_id
                    ORDER BY w.id, cst.start_time;
                ELSE
                    -- Return a message if no class_id is found for the student
                    SELECT \'Student not found or not assigned to a class\' AS message;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS StudentFetchMyClassTimetable');
    }
};
