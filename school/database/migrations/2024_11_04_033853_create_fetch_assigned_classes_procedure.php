<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateFetchAssignedClassesProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Drop the procedure if it already exists
        DB::unprepared('DROP PROCEDURE IF EXISTS FetchAssignedClasses');

        // Create the FetchAssignedClasses procedure
        DB::unprepared('
            CREATE PROCEDURE FetchAssignedClasses(
               
            )
            BEGIN
                SELECT 
                    assign_class_teacher.*, 
                    class.name AS class_name, 
                    teacher.name AS teacher_name,
                    users.name AS created_by_name
                FROM 
                    assign_class_teacher
                JOIN 
                    class ON class.id = assign_class_teacher.class_id
                JOIN 
                    teacher ON teacher.id = assign_class_teacher.teacher_id
                JOIN 
                    users ON users.id = assign_class_teacher.created_by
                WHERE 
                    assign_class_teacher.is_delete = 0
                    
                ORDER BY 
                    assign_class_teacher.id DESC
                LIMIT 100;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Drop the procedure if it already exists
        DB::unprepared('DROP PROCEDURE IF EXISTS FetchAssignedClasses');
    }
}
