<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Request;

class HomeworkModel extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = 'homework';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'class_id',
        'subject_id',
        'homework_date',
        'submission_date',
        'description',
        'document_file',
        'created_by'
    ];

    // Method to get a single record by ID
    static public function getSingle($id)
    {
        return self::find($id);
    }

    // Method to get records with various filters
    static public function getRecord($student_id = null)
    {
        $return = HomeworkModel::select('homework.*', 'class.name as class_name', 'subject.name as subject_name', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'homework.created_by')
            ->join('class', 'class.id', '=', 'homework.class_id')
            ->join('subject', 'subject.id', '=', 'homework.subject_id')
            ->where('homework.is_delete', '=', 0);

        if ($student_id) {
            $student = \DB::table('student')->where('id', $student_id)->first();
            if (!$student) {
                return [];
            }
            $class_id = $student->class_id;
            $return = $return->where('homework.class_id', '=', $class_id);
        }

        if (!empty(Request::get('class_name'))) {
            $return = $return->where('class.name', 'like', '%' . Request::get('class_name') . '%');
        }

        if (!empty(Request::get('subject_name'))) {
            $return = $return->where('subject.name', 'like', '%' . Request::get('subject_name') . '%');
        }

        if (!empty(Request::get('from_homework_date'))) {
            $return = $return->where('homework.homework_date', '>=', Request::get('from_homework_date'));
        }

        if (!empty(Request::get('to_homework_date'))) {
            $return = $return->where('homework.homework_date', '<=', Request::get('to_homework_date'));
        }

        if (!empty(Request::get('from_submission_date'))) {
            $return = $return->where('homework.submission_date', '>=', Request::get('from_submission_date'));
        }

        if (!empty(Request::get('to_submission_date'))) {
            $return = $return->where('homework.submission_date', '<=', Request::get('to_submission_date'));
        }

        if (!empty(Request::get('from_created_date'))) {
            $return = $return->whereDate('homework.created_at', '>=', Request::get('from_created_date'));
        }

        if (!empty(Request::get('to_created_date'))) {
            $return = $return->whereDate('homework.created_at', '<=', Request::get('to_created_date'));
        }

        return $return->orderBy('homework.id', 'desc')->paginate(20);
    }

    // Method to get records for teachers based on class IDs
    static public function getRecordTeacher($class_ids)
    {
        $return = HomeworkModel::select('homework.*', 'class.name as class_name', 'subject.name as subject_name', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'homework.created_by')
            ->join('class', 'class.id', '=', 'homework.class_id')
            ->join('subject', 'subject.id', '=', 'homework.subject_id')
            ->whereIn('homework.class_id', $class_ids)
            ->where('homework.is_delete', '=', 0);

        if (!empty(Request::get('class_name'))) {
            $return = $return->where('class.name', 'like', '%' . Request::get('class_name') . '%');
        }

        if (!empty(Request::get('subject_name'))) {
            $return = $return->where('subject.name', 'like', '%' . Request::get('subject_name') . '%');
        }

        if (!empty(Request::get('from_homework_date'))) {
            $return = $return->where('homework.homework_date', '>=', Request::get('from_homework_date'));
        }

        if (!empty(Request::get('to_homework_date'))) {
            $return = $return->where('homework.homework_date', '<=', Request::get('to_homework_date'));
        }

        if (!empty(Request::get('from_submission_date'))) {
            $return = $return->where('homework.submission_date', '>=', Request::get('from_submission_date'));
        }

        if (!empty(Request::get('to_submission_date'))) {
            $return = $return->where('homework.submission_date', '<=', Request::get('to_submission_date'));
        }

        if (!empty(Request::get('from_created_date'))) {
            $return = $return->whereDate('homework.created_at', '>=', Request::get('from_created_date'));
        }

        if (!empty(Request::get('to_created_date'))) {
            $return = $return->whereDate('homework.created_at', '<=', Request::get('to_created_date'));
        }

        return $return->orderBy('homework.id', 'desc')->paginate(20);
    }

    // Method to get records for students based on class ID and student ID
    static public function getRecordStudent($class_id, $student_id)
    {
        $return = HomeworkModel::select('homework.*', 'class.name as class_name', 'subject.name as subject_name', 'users.name as created_by_name')
            ->join('users', 'users.id', '=', 'homework.created_by')
            ->join('class', 'class.id', '=', 'homework.class_id')
            ->join('subject', 'subject.id', '=', 'homework.subject_id')
            ->where('homework.class_id', '=', $class_id)
            ->where('homework.is_delete', '=', 0)
            ->whereNotIn('homework_id', function($query) use ($student_id) {
                $query->select('homework_submit.homework_id')
                      ->from('homework_submit')
                      ->where('homework_submit.student_id', '=', $student_id);
            });

        if (!empty(Request::get('subject_name'))) {
            $return = $return->where('subject.name', 'like', '%' . Request::get('subject_name') . '%');
        }

        if (!empty(Request::get('from_homework_date'))) {
            $return = $return->where('homework.homework_date', '>=', Request::get('from_homework_date'));
        }

        if (!empty(Request::get('to_homework_date'))) {
            $return = $return->where('homework.homework_date', '<=', Request::get('to_homework_date'));
        }

        if (!empty(Request::get('from_submission_date'))) {
            $return = $return->where('homework.submission_date', '>=', Request::get('from_submission_date'));
        }

        if (!empty(Request::get('to_submission_date'))) {
            $return = $return->where('homework.submission_date', '<=', Request::get('to_submission_date'));
        }

        if (!empty(Request::get('from_created_date'))) {
            $return = $return->whereDate('homework.created_at', '>=', Request::get('from_created_date'));
        }

        if (!empty(Request::get('to_created_date'))) {
            $return = $return->whereDate('homework.created_at', '<=', Request::get('to_created_date'));
        }

        return $return->orderBy('homework.id', 'desc')->paginate(20);
    }

    // Method to get document URL if available
    public function getDocument()
    {
        if ($this->document_file) {
            return url('uploads/homework/' . $this->document_file);
        }
        return null;
    }
}
