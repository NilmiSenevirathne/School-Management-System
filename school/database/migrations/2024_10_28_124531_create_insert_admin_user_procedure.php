<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateInsertAdminUserProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop the procedure if it already exists
        DB::unprepared('DROP PROCEDURE IF EXISTS InsertAdminUser');

        // Create the InsertAdminUser procedure
        DB::unprepared('
            CREATE PROCEDURE InsertAdminUser(
                IN p_name VARCHAR(255),
                IN p_last_name VARCHAR(255),
                IN p_address VARCHAR(255),
                IN p_contact VARCHAR(15),
                IN p_email VARCHAR(255),
                IN p_password_hash VARCHAR(255)
            )
            BEGIN
                DECLARE admin_id INT;

                -- Insert into admin table
                INSERT INTO admin (name, last_name, address, contact, email, password, user_type, created_at)
                VALUES (p_name, p_last_name, p_address, p_contact, p_email, p_password_hash, 1, NOW());
                SET admin_id = LAST_INSERT_ID();

                -- Insert into users table
                INSERT INTO users (name, last_name, email, password, user_type, created_at)
                VALUES (p_name, p_last_name, p_email, p_password_hash, 1, NOW());
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
        // Drop the InsertAdminUser procedure if it exists
        DB::unprepared('DROP PROCEDURE IF EXISTS InsertAdminUser');
    }
}
