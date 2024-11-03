<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'created_by',
        'created_at',
    ];

    static public function CheckAlreadyMark($student_id, $exam_id,$class_id,$subject_id)
    {

        return MarksRegister::where('student_id', '=', $student_id)->where('exam_id','=', $exam_id)->where('class_id', '=', $class_id)->where('subject_id','=',$subject_id)->first();
    }
}
