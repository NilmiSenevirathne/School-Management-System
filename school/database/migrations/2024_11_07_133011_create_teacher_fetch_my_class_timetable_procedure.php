<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTeacherFetchMyClassTimetableProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE PROCEDURE TeacherFetchMyClassTimetable(IN teacher_email VARCHAR(255), IN class_id INT, IN subject_id INT)
            BEGIN
                DECLARE teacher_exists BOOLEAN;

                -- Check if the teacher exists based on email, ensuring collation match
                SET teacher_exists = EXISTS (
                    SELECT 1 
                    FROM teacher 
                    WHERE email = teacher_email COLLATE utf8mb4_unicode_ci
                );

                -- If the teacher exists, fetch timetable
                IF teacher_exists THEN
                    SELECT 
                        w.id AS week_id, 
                        w.name AS week_name, 
                        s.name AS subject_name, 
                        cst.start_time, 
                        cst.end_time, 
                        cst.room_number
                    FROM class_subject_timetable AS cst
                    JOIN subject AS s ON cst.subject_id = s.id
                    JOIN week AS w ON cst.week_id = w.id
                    WHERE cst.class_id = class_id
                      AND cst.subject_id = subject_id
                    ORDER BY w.id, cst.start_time;
                ELSE
                    SELECT "Teacher not found or not assigned to a class" AS message;
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
        DB::unprepared('DROP PROCEDURE IF EXISTS TeacherFetchMyClassTimetable');
    }
}
