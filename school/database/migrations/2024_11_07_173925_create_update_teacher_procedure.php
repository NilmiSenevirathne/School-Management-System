<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUpdateTeacherProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE PROCEDURE updateTeacher(
                IN homeworkId BIGINT,
                IN classId BIGINT,
                IN subjectId BIGINT,
                IN homeworkDate DATE,
                IN submissionDate DATE,
                IN description TEXT,
                IN documentFile VARCHAR(255),
                IN userId INT
            )
            BEGIN
                UPDATE homework
                SET 
                    class_id = IFNULL(classId, class_id),
                    subject_id = IFNULL(subjectId, subject_id),
                    homework_date = IFNULL(homeworkDate, homework_date),
                    submission_date = IFNULL(submissionDate, submission_date),
                    description = IFNULL(description, description),
                    document_file = IFNULL(documentFile, document_file),
                    created_by = userId
                WHERE id = homeworkId;
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
        DB::unprepared('DROP PROCEDURE IF EXISTS updateTeacher');
    }
}
