<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateInsertHomeworkProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE PROCEDURE InsertHomework(
                IN p_class_id BIGINT,
                IN p_subject_id BIGINT,
                IN p_homework_date DATE,
                IN p_submission_date DATE,
                IN p_description TEXT,
                IN p_document_file VARCHAR(255),
                IN p_created_by INT
            )
            BEGIN
                INSERT INTO homework (
                    class_id, subject_id, homework_date, submission_date, 
                    description, document_file, created_by, created_at, updated_at
                ) VALUES (
                    p_class_id, p_subject_id, p_homework_date, p_submission_date, 
                    p_description, p_document_file, p_created_by, NOW(), NOW()
                );
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
        DB::unprepared('DROP PROCEDURE IF EXISTS InsertHomework');
    }
}
