<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateInsertTeacherProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE PROCEDURE insertTeacher(
                IN classId BIGINT,
                IN subjectId BIGINT,
                IN homeworkDate DATE,
                IN submissionDate DATE,
                IN description TEXT,
                IN documentFile VARCHAR(255),
                IN userId INT
            )
            BEGIN
                INSERT INTO homework (class_id, subject_id, homework_date, submission_date, description, document_file, created_by, created_at, updated_at)
                VALUES (classId, subjectId, homeworkDate, submissionDate, description, documentFile, userId, NOW(), NOW());
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
        DB::unprepared('DROP PROCEDURE IF EXISTS insertTeacher');
    }
}
