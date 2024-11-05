<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateAddClassProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE PROCEDURE add_class(
                IN p_name VARCHAR(255),
                IN p_status TINYINT,
                IN p_created_by INT
            )
            BEGIN
                INSERT INTO class (name, status, created_by)
                VALUES (p_name, p_status, p_created_by);
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
        DB::unprepared('DROP PROCEDURE IF EXISTS add_class');
    }
}
