<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::unprepared('
            CREATE PROCEDURE GetStudentClassA(IN classID VARCHAR(255))
            BEGIN
                SELECT student.id, student.name, student.last_name
                FROM student
                WHERE user_type = 3
                    AND is_delete = 0
                    AND class_id = classID
                ORDER BY id DESC;
            END
       ');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS GetStudentClassA');

    }
};
