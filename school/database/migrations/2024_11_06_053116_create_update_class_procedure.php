<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUpdateClassProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE PROCEDURE UpdateClass(IN class_id INT, IN class_name VARCHAR(255), IN class_status VARCHAR(50))
            BEGIN
                UPDATE class
                SET name = class_name, status = class_status
                WHERE id = class_id;
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
        DB::unprepared('DROP PROCEDURE IF EXISTS UpdateClass');
    }
}
