<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateAdminUpdateClassTimetableProcedure extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the stored procedure to insert or update class timetable
        DB::unprepared('
           

            CREATE PROCEDURE insert_or_update_timetable(
                IN class_id INT, 
                IN subject_id INT, 
                IN week_id INT, 
                IN start_time TIME, 
                IN end_time TIME, 
                IN room_number VARCHAR(255)
            )
            BEGIN
                -- Check if a record already exists
                IF EXISTS (SELECT 1 FROM class_subject_timetable WHERE class_id = class_id AND subject_id = subject_id AND week_id = week_id) THEN
                    -- If exists, update the record
                    UPDATE class_subject_timetable
                    SET start_time = start_time, end_time = end_time, room_number = room_number
                    WHERE class_id = class_id AND subject_id = subject_id AND week_id = week_id;
                ELSE
                    -- If not exists, insert a new record
                    INSERT INTO class_subject_timetable (class_id, subject_id, week_id, start_time, end_time, room_number)
                    VALUES (class_id, subject_id, week_id, start_time, end_time, room_number);
                END IF;
            END 

          
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the stored procedure
        DB::unprepared('DROP PROCEDURE IF EXISTS insert_or_update_timetable');
    }
}
