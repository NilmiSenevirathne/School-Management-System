<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateDeleteAssignedClassTeacherProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE PROCEDURE delete_assigned_class_teacher(IN assign_id INT)
            BEGIN
                UPDATE assign_class_teacher
                SET is_delete = 1
                WHERE id = assign_id AND is_delete = 0;
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
        DB::unprepared('DROP PROCEDURE IF EXISTS delete_assigned_class_teacher');
    }
}