<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;

    protected $table = 'student';

    protected $fillable = [
        'id',
        'parent_id',
        'admission_number',
        'roll_number',
        'class_id',
        'name',
        'last_name',
        'address',
        'gender',
        'date_of_birth',
        'contact',
        'admission_date',
        'status',
        'profile_picture',
        'email ',
        'password',
        'user_type',
        'is_delete',
        'created_at',
    ];

    public static function getSingleStudent($id)
    {

        return self::find($id);
    }

    //get student account information

    public static function getStudentAccount($email)
    {
        return self::where('email', $email)->first();
    }

    public static function getStudent()
    {
        $return = self::select('student.*', 'class.name as class_name', 'parent.name as parent_name', 'parent.last_name as parent_last_name')
            ->leftJoin('parent as parent', 'parent.id', '=', 'student.parent_id')
            ->join('class', 'class.id', '=', 'student.class_id', 'left')
            ->where('student.is_delete', '=', 0);

        if (! empty(Request::get('name'))) {
            $return = $return->where('student.name', 'like', '%'.Request::get('name').'%');
        }
        if (! empty(Request::get('last_name'))) {
            $return = $return->where('student.last_name', 'like', '%'.Request::get('last_name').'%');
        }

        if (! empty(Request::get('email'))) {
            $return = $return->where('student.email', 'like', '%'.Request::get('email').'%');
        }
        if (! empty(Request::get('class'))) {
            $return = $return->where('class.name', 'like', '%'.Request::get('class').'%');
        }
        if (! empty(Request::get('gender'))) {
            $return = $return->where('student.gender', '=', Request::get('gender'));
        }

        if (! empty(Request::get('admission_date'))) {
            $return = $return->whereDate('student.admission_date', '=', Request::get('admission_date'));
        }

        $return = $return->orderBy('student.id', 'desc')
            ->paginate(20);

        return $return;
    }

    public function getStudentProfile()
    {
        if (! empty($this->profile_picture) && file_exists('uploads/profile/'.$this->profile_picture)) {
            return url('uploads/profile/'.$this->profile_picture);
        } else {
            return '';
        }
    }

    // static public function getSearchStudent()
    // {
    //    if(!empty(Request::get('id')) || !empty(Request::get('name')) || !empty(Request::get('last_name')) || !empty(Request::get('email')))
    //    {
    //     $return = self::select('student.*','class.name as class_name')//,'parent.name as parent_name'
    //     //->join('parent as parent' ,'parent.id', '=' , 'student.parent_id')
    //     ->join('class','class.id','=','student.class_id','left')
    //     ->where('student.is_delete','=',0);

    //     if(!empty(Request::get('id')))
    //     {
    //        $return = $return->where('student.id','=',Request::get('id'));
    //     }
    //     if(!empty(Request::get('name')))
    //     {
    //        $return = $return->where('student.name','like','%'.Request::get('name').'%');
    //     }
    //     if(!empty(Request::get('last_name')))
    //     {
    //        $return = $return->where('student.last_name','like','%'.Request::get('last_name').'%');
    //     }

    //      if(!empty(Request::get('email')))
    //      {
    //         $return = $return->where('student.email','like','%'.Request::get('email').'%');
    //      }

    //     $return = $return->orderBy('student.id','desc')
    //     ->limit(50)
    //     ->get();

    //     return $return;
    //    }
    // }
    public static function getSearchStudent()
    {
        if (! empty(Request::get('id')) || ! empty(Request::get('name')) || ! empty(Request::get('last_name')) || ! empty(Request::get('email'))) {
            $return = self::select('student.*', 'parent.name as parent_name')//  $return = self::select('student.*', 'class.name as class_name', 'parent.name as parent_name')
                //->leftJoin('class', 'class.id', '=', 'student.class_id')  // Ensure the 'class' join is a left join
                ->leftJoin('parent as parent', 'parent.id', '=', 'student.parent_id')  // Use leftJoin for parent join
                ->where('student.is_delete', '=', 0);

            // Search by ID, name, last name, or email
            if (! empty(Request::get('id'))) {
                $return->where('student.id', '=', Request::get('id'));
            }
            if (! empty(Request::get('name'))) {
                $return->where('student.first_name', 'LIKE', '%'.Request::get('name').'%');
            }
            if (! empty(Request::get('last_name'))) {
                $return->where('student.last_name', 'LIKE', '%'.Request::get('last_name').'%');
            }
            if (! empty(Request::get('email'))) {
                $return->where('student.email', 'LIKE', '%'.Request::get('email').'%');
            }

            // Return the results
            return $return->get();
        }

        return [];
    }

    public static function getMyStudent($parent_id)
    {
        return self::select('student.*', 'parent.name as parent_name', 'parent.last_name as parent_last_name')
            ->leftJoin('parent as parent', 'parent.id', '=', 'student.parent_id')
            ->where('student.parent_id', '=', $parent_id)
            ->where('student.is_delete', '=', 0)
            ->get();
    }

    public static function getStudentClass($class_id)
    {
        return  self::select('student.id','student.name','student.last_name')
            ->where('student.is_delete', '=', 0)
            ->where('student.class_id', '=', $class_id)
            ->orderBy('student.id', 'desc')
            ->get(20);
    }

    static public function getStudentClassA($class_id)
    {
       return self::select('student.id','student.name','student.last_name')
                        ->where('user_type','=',3)
                        ->where('is_delete','=',0)
                        ->where('class_id','=',$class_id)
                        ->orderBy('id','desc')
                        ->get();
            
    }

    static public function getAttendance($student_id, $class_id, $attendance_date)
    {
        return StudentAttendanceModel::CheckAlreadyAttendance($student_id, $class_id, $attendance_date);
    }


    // Teacher-side method to fetch students linked to the teacher’s class
    public static function getTeacherStudent($teacher_id)

    {
        return DB::table('teacher_fetch_mystudent_view')
            ->where('teacher_id', '=', $teacher_id)  // Now directly filter by teacher_id
            ->distinct()
            ->orderBy('id', 'desc')
            ->paginate(20); // Paginate results
    

   
    return self::select('student.*', 'class.name as class_name')
        ->leftJoin('class', 'class.id', '=', 'student.class_id')
        ->leftJoin('assign_class_teacher', 'assign_class_teacher.class_id', '=', 'class.id')
        ->where('assign_class_teacher.teacher_id', '=', $teacher_id)
        ->where('student.is_delete', '=', 0) // Ensure you're only getting non-deleted students
        ->distinct()
        ->orderBy('student.id', 'desc')
        ->paginate(20); // Paginate results
   }



    

    }
