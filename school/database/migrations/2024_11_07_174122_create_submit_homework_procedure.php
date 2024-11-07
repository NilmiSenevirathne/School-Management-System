<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSubmitHomeworkProcedure extends Migration
{
    public function up()
    {
        DB::unprepared('
            DROP PROCEDURE IF EXISTS submit_homework_procedure;

            CREATE PROCEDURE submit_homework_procedure(
                IN homework_id INT,
                IN student_id INT,
                IN description TEXT,
                IN document_file VARCHAR(255)
            )
            BEGIN
                DECLARE existing_submission INT;

                -- Check if the student has already submitted this homework
                SELECT COUNT(*) INTO existing_submission
                FROM homework_submit
                WHERE homework_id = homework_id AND student_id = student_id;

                -- If the student has already submitted this homework, update the submission
                IF existing_submission > 0 THEN
                    UPDATE homework_submit
                    SET description = description, document_file = document_file
                    WHERE homework_id = homework_id AND student_id = student_id;
                ELSE
                    -- Otherwise, insert a new record for the submission
                    INSERT INTO homework_submit (homework_id, student_id, description, document_file)
                    VALUES (homework_id, student_id, description, document_file);
                END IF;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS submit_homework_procedure');
    }
}
