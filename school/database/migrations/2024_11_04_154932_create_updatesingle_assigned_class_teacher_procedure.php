<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUpdateSingleAssignedClassTeacherProcedure extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE PROCEDURE update_single_assigned_class_teacher(
                IN teacher_assign_id INT,
                IN class_id INT,
                IN teacher_id INT,
                IN status TINYINT,
                IN updated_by INT
            )
            BEGIN
                UPDATE assign_class_teacher
                SET class_id = class_id,
                    teacher_id = teacher_id,
                    status = status,
                    updated_by = updated_by,
                    updated_at = NOW() 
                WHERE id = teacher_assign_id AND is_delete = 0;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS update_single_assigned_class_teacher");
    }
};
