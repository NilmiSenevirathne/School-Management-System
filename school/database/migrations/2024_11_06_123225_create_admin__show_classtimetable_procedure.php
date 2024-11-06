<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateAdminShowClassTimetableProcedure extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Define the SQL for creating the stored procedure
        DB::statement('
            

            CREATE PROCEDURE admin_show_classtimetable(IN class_id INT, IN subject_id INT)
            BEGIN
                -- Select the days of the week along with their timetable details (if available)
                SELECT 
                    dow.id AS week_id,
                    dow.name AS week_name,
                    COALESCE(cst.start_time, "") AS start_time,
                    COALESCE(cst.end_time, "") AS end_time,
                    COALESCE(cst.room_number, "") AS room_number
                FROM 
                    week AS dow  -- Assuming this table contains days 1 to 7 (Monday to Sunday)
                LEFT JOIN 
                    class_subject_timetable AS cst
                    ON cst.week_id = dow.id
                    AND cst.class_id = class_id
                    AND cst.subject_id = subject_id
                ORDER BY 
                    dow.id;  -- Order by the days of the week (1 to 7)

            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the stored procedure if it exists
        DB::statement('DROP PROCEDURE IF EXISTS admin_show_classtimetable');
    }
}
