<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUserCountFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE FUNCTION getUserCount(userType INT) 
            RETURNS INT
            DETERMINISTIC
            BEGIN
                DECLARE userCount INT;
                SELECT COUNT(id) INTO userCount
                FROM users
                WHERE user_type = userType AND is_delete = 0;
                RETURN userCount;
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
        DB::unprepared('DROP FUNCTION IF EXISTS getUserCount');
    }
}

