<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateInsertAdminProcedure extends Migration
{
    public function up()
    {
        DB::unprepared('
            CREATE PROCEDURE InsertAdminAndUser(
                IN in_name VARCHAR(255),
                IN in_last_name VARCHAR(255),
                IN in_address VARCHAR(255),
                IN in_contact VARCHAR(15),
                IN in_email VARCHAR(255),
                IN in_password VARCHAR(255),
                IN in_user_type INT,
                IN in_profile_picture VARCHAR(255)
            )
            BEGIN
                -- Insert into admin table
                INSERT INTO admin (name, last_name, address, contact, email, password, user_type, profile_picture, created_at)
                VALUES (in_name, in_last_name, in_address, in_contact, in_email, in_password, in_user_type, in_profile_picture, NOW());
                
                -- Insert into users table for login
                INSERT INTO users (name, last_name, email, password, user_type, created_at)
                VALUES (in_name, in_last_name, in_email, in_password, in_user_type, NOW());
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS InsertAdminAndUser');
    }
}
