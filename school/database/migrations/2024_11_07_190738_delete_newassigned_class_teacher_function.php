<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class DeleteNewAssignedClassTeacherFunction extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
     

            CREATE FUNCTION delete_newassigned_class_teacher(assign_id INT)
            RETURNS BOOLEAN
            DETERMINISTIC
            BEGIN
                DECLARE result BOOLEAN DEFAULT FALSE;

                UPDATE assign_class_teacher
                SET is_delete = 1
                WHERE id = assign_id AND is_delete = 0;

                IF ROW_COUNT() > 0 THEN
                    SET result = TRUE;
                ELSE
                    SET result = FALSE;
                END IF;

                RETURN result;
            END 
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS delete_assigned_class_teacher');
    }
};
