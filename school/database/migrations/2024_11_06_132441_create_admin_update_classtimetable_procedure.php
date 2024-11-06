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
        // Create stored procedure using DB::statement
        DB::unprepared('
            CREATE PROCEDURE admin_update_class_timetable(
                IN class_id INT,
                IN subject_id INT,
                IN timetable_data JSON
            )
            BEGIN
                -- Declare variables for the loop
                DECLARE i INT DEFAULT 0;
                DECLARE timetable_count INT;
                DECLARE week_id INT;
                DECLARE start_time TIME;
                DECLARE end_time TIME;
                DECLARE room_number VARCHAR(10);

                -- Get the count of the timetable data array
                SET timetable_count = JSON_LENGTH(timetable_data);

                -- Loop through the timetable data and update/insert the records
                WHILE i < timetable_count DO
                    -- Extract values from JSON object for each timetable entry
                    SET week_id = JSON_UNQUOTE(JSON_EXTRACT(timetable_data, CONCAT("$[", i, "].week_id")));
                    SET start_time = JSON_UNQUOTE(JSON_EXTRACT(timetable_data, CONCAT("$[", i, "].start_time")));
                    SET end_time = JSON_UNQUOTE(JSON_EXTRACT(timetable_data, CONCAT("$[", i, "].end_time")));
                    SET room_number = JSON_UNQUOTE(JSON_EXTRACT(timetable_data, CONCAT("$[", i, "].room_number")));

                    -- Ensure values are not NULL before inserting/updating
                    IF week_id IS NOT NULL AND start_time IS NOT NULL AND end_time IS NOT NULL AND room_number IS NOT NULL THEN
                        -- Check if this record exists, update it if it does, insert if it doesnot
                        IF EXISTS (
                            SELECT 1 
                            FROM class_subject_timetable 
                            WHERE class_id = class_id AND subject_id = subject_id AND week_id = week_id
                        ) THEN
                            -- Update existing record
                            UPDATE class_subject_timetable
                            SET start_time = start_time, end_time = end_time, room_number = room_number
                            WHERE class_id = class_id AND subject_id = subject_id AND week_id = week_id;
                        ELSE
                            -- Insert new record
                            INSERT INTO class_subject_timetable (class_id, subject_id, week_id, start_time, end_time, room_number)
                            VALUES (class_id, subject_id, week_id, start_time, end_time, room_number);
                        END IF;
                    END IF;

                    -- Increment the loop counter
                    SET i = i + 1;
                END WHILE;

            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the stored procedure if it exists
        DB::unprepared('DROP PROCEDURE IF EXISTS admin_update_class_timetable');
    }
}
