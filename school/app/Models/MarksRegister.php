<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MarksRegister extends Model
{
    use HasFactory;

    protected $table = 'marks_register';
    protected $fillable = [
        'id',
        'student_id',
        'exam_id',
        'class_id',
        'subject_id',
        'home_work',
        'test_work',
        'exam',
        'full_marks',
        'passing_marks',
        'created_by',
        'created_at',
    ];

    static public function CheckAlreadyMark($student_id, $exam_id,$class_id,$subject_id)
    {

        return MarksRegister::where('student_id', '=', $student_id)->where('exam_id','=', $exam_id)->where('class_id', '=', $class_id)->where('subject_id','=',$subject_id)->first();
    }

    // static public function getExam($student_id)
    // {
    //     return MarksRegister::select('marks_register.*','exam.name as exam_name')
    //         ->join('exam','exam.id','=', 'marks_register.exam_id')
    //         ->where('marks_register.student_id', '=', $student_id)
    //         ->groupBy('marks_register.exam_id')
    //         ->get();
    // }
    static public function getExam($student_id)
    {
        return DB::table('student_exam_results')
            ->where('student_id', '=', $student_id)
            ->groupBy('exam_id')  // Adjust as needed
            ->get();
    }

    // static public function getExamSubject($exam_id,$student_id)
    // {
    //     return MarksRegister::select('marks_register.*','exam.name as exam_name','subject.name as subject_name')
    //         ->join('exam','exam.id','=', 'marks_register.exam_id')
    //         ->join('subject','subject.id','=', 'marks_register.subject_id')
    //         ->where('marks_register.exam_id', '=', $exam_id)
    //         ->where('marks_register.student_id', '=', $student_id)

    //         ->get();
    // }

    public static function getExamSubject($exam_id, $student_id)
{
    return DB::table('exam_subject_view')
        ->where('exam_id', '=', $exam_id)
        ->where('student_id', '=', $student_id)
        ->get();
}

    
}
