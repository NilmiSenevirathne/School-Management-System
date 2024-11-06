<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateGetTeacherStudentCountFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE FUNCTION getTeacherStudentCount(teacher_id INT) 
            RETURNS INT DETERMINISTIC
            BEGIN
                DECLARE student_count INT;
                SELECT COUNT(DISTINCT student.id) INTO student_count
                FROM student
                LEFT JOIN class ON class.id = student.class_id
                LEFT JOIN assign_class_teacher ON assign_class_teacher.class_id = class.id
                WHERE assign_class_teacher.teacher_id = teacher_id
                AND student.is_delete = 0;
                RETURN student_count;
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
        DB::unprepared('DROP FUNCTION IF EXISTS getTeacherStudentCount');
    }
}


