<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    use HasFactory;

    protected $table = 'exam_schedule';
    protected $fillable = [
        'id',
        'exam_id',
        'class_id',
        'subject_id',
        'exam_date',
        'start_time',
        'end_time',
        'location',
        'full_marks',
        'passing_marks',
        'created_by',
        'created_at',
    ];
    static public function getSingle($id)
    {
        return self::find($id);
    }

    static public function getRecordSingle($exam_id,$class_id,$subject_id)
    {

        return self::where('exam_id', '=', $exam_id)->where('class_id', '=', $class_id)->where('subject_id', '=', $subject_id)->first();
    }

    static public function deleteRecord($exam_id,$class_id)
    {

        self::where('exam_id', '=', $exam_id)->where('class_id', '=', $class_id)->delete();

    }

    static public function getExam($class_id)
{
    return ExamSchedule::select('exam_schedule.*', 'exam.name as exam_name') // Alias 'name' as 'exam_name'
        ->join('exam', 'exam.id', '=', 'exam_schedule.exam_id') // Update here to match your join conditions
        ->where('exam_schedule.class_id', '=', $class_id)
        ->groupBy('exam_schedule.exam_id')
        ->orderBy('exam_schedule.id', 'desc')
        ->get();
}


static public function getExamTimetable($exam_id, $class_id)
{
    return ExamSchedule::select('exam_schedule.*', 'subject.name as subject_name', 'subject.type as subject_type')
        ->join('subject', 'subject.id', '=', 'exam_schedule.subject_id') // Ensure correct join condition
        ->where('exam_schedule.exam_id', '=', $exam_id)
        ->where('exam_schedule.class_id', '=', $class_id)
        ->distinct() // Ensures unique records
        ->get();
}

static public function getSubject($exam_id, $class_id)
{
    return ExamSchedule::select('exam_schedule.*', 'subject.name as subject_name', 'subject.type as subject_type')
        ->join('subject', 'subject.id', '=', 'exam_schedule.subject_id') // Ensure correct join condition
        ->where('exam_schedule.exam_id', '=', $exam_id)
        ->where('exam_schedule.class_id', '=', $class_id)
        ->distinct() // Ensures unique records
        ->get();
}

static public function getMark($student_id,$exam_id,$class_id,$subject_id)
{
    return MarksRegister::CheckAlreadyMark($student_id,$exam_id,$class_id,$subject_id);
}
}
