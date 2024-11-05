<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateGradeTrigger extends Migration
{
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER set_grade_before_insert
            BEFORE INSERT ON marks_register
            FOR EACH ROW
            BEGIN
                DECLARE grade_name VARCHAR(2);
                
                -- Calculate grade based on total_marks
                IF NEW.total_marks >= 75 THEN
                    SET grade_name = "A";
                ELSEIF NEW.total_marks >= 65 THEN
                    SET grade_name = "B";
                ELSEIF NEW.total_marks >= 55 THEN
                    SET grade_name = "C";
                ELSEIF NEW.total_marks >= 45 THEN
                    SET grade_name = "D";
                ELSEIF NEW.total_marks >= 35 THEN
                    SET grade_name = "S";
                ELSE
                    SET grade_name = "F";
                END IF;

                -- Set grade before the record is inserted
                SET NEW.grade = grade_name;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS set_grade_before_insert');
    }
}
