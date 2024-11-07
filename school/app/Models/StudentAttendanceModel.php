<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use Request;

class StudentAttendanceModel extends Model
{
    use HasFactory;

    protected $table = 'student_attendance';

    static public function CheckAlreadyAttendance($student_id, $class_id, $attendance_date)
    {
        return StudentAttendanceModel::where('student_id','=',$student_id)->where('class_id','=',$class_id)->where('attendance_date','=',$attendance_date)->first();
    }

    static function getRecord()
    {
        $return = StudentAttendanceModel::select('student_attendance.*','class.name as class_name','student.name as student_name','student.last_name as student_last_name','createdby.name as created_name')
        ->join('class','class.id','=','student_attendance.class_id')
        ->join('student','student.id','=','student_attendance.student_id')
        ->join('users as createdby','createdby.id','=','student_attendance.created_by');

        if(!empty(Request::get('student_id')))
        {
            $return = $return->where('student_attendance.student_id','=',Request::get('student_id'));
        }

        if(!empty(Request::get('student_name')))
        {
            $return = $return->where('student.name','like','%'.Request::get('student_name').'%');
        }

        if(!empty(Request::get('class_id')))
        {
            $return = $return->where('student_attendance.class_id','=',Request::get('class_id'));
        }

        if(!empty(Request::get('attendance_date')))
        {
            $return = $return->where('student_attendance.attendance_date','=',Request::get('attendance_date'));
        }

        if(!empty(Request::get('attendance_type')))
        {
            $return = $return->where('student_attendance.attendance_type','=',Request::get('attendance_type'));
        }

       $return = $return->orderBy('student_attendance.id','desc')
        ->paginate(10);
    return $return;
    }

    static function getRecordTeacher($class_ids)
    {
        if(!empty($class_ids))
        {
                   $query = DB::table('student_attendance_view')
                    ->select(
                        'student_attendance_view.*',
                        'class_name',
                        'student_name',
                        'student_last_name',
                        'created_name'
                    )
                    ->whereIn('class_id', $class_ids);

                // Apply filters based on request parameters
                if (!empty(Request::get('student_id'))) {
                    $query->where('student_id', Request::get('student_id'));
                }

                if (!empty(Request::get('student_name'))) {
                    $query->where('student_name', 'like', '%' . Request::get('student_name') . '%');
                }

                if (!empty(Request::get('class_id'))) {
                    $query->where('class_id', Request::get('class_id'));
                }

                if (!empty(Request::get('attendance_date'))) {
                    $query->where('attendance_date', Request::get('attendance_date'));
                }

                if (!empty(Request::get('attendance_type'))) {
                    $query->where('attendance_type', Request::get('attendance_type'));
                }

                // Order the results and paginate
                return $query->orderBy('id', 'desc')->paginate(10);
        }
        else
        {
            return new LengthAwarePaginator([], 0, 10);
        }
       
    }
}
