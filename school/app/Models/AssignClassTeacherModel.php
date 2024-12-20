<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Request;

class AssignClassTeacherModel extends Model
{
    use HasFactory;

    protected $table = 'assign_class_teacher';
    protected $fillable = ['class_id', 'teacher_id', 'status', 'created_by', 'is_delete'];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getSingle($id)
    {
       return self::find($id);
    }

    public static function getRecord()
    {
        $return = self::select(
                'assign_class_teacher.*', 
                'class.name as class_name', 
                'teacher.name as teacher_name',
                'users.name as created_by_name'
            )
            ->join('class', 'class.id', '=', 'assign_class_teacher.class_id')
            ->join('teacher', 'teacher.id', '=', 'assign_class_teacher.teacher_id')
            ->join('users', 'users.id', '=', 'assign_class_teacher.created_by')
            ->where('assign_class_teacher.is_delete', '=', 0);

        if (!empty(Request::get('class_name'))) {
            $return = $return->where('class.name', 'like', '%' . Request::get('class_name') . '%');
        }
        if (!empty(Request::get('teacher_name'))) {
            $return = $return->where('teacher.name', 'like', '%' . Request::get('teacher_name') . '%');
        }
        if (!empty(Request::get('status'))) {
            $status = (Request::get('status') == 100) ? 0 : 1; 
            $return = $return->where('assign_class_teacher.status', '=', $status);
        }
        if (!empty(Request::get('date'))) {
            $return = $return->whereDate('assign_class_teacher.created_at', '=', Request::get('date'));
        }

        return $return->orderBy('assign_class_teacher.id', 'desc')
                      ->paginate(100);
    }

    // Execute the stored procedure and get the results
    public static function getMyClassSubject($teacher_id)
   {
    
    return DB::select('CALL TeacherFetchMyClassAndSubject(?)', [$teacher_id]);
   }

    public static function getAlreadyFirst($class_id, $teacher_id)
    {
        return self::where('class_id', '=', $class_id)->where('teacher_id', '=', $teacher_id)->first();
    }

    static public function getAssignTeacherID($class_id)
    {
        return self::where('class_id', '=', $class_id)->where('is_delete', '=', 0)->get();
    }

    static public function deleteTeacher($class_id)
    {
        return self::where('class_id', '=', $class_id)->delete();
    }

    // static public function getMyClassSubjectGroup($teacher_id)
    // {
    //     return AssignClassTeacherModel::select(
    //         'assign_class_teacher.*', 
    //         'class.name as class_name',
    //         'class.id as class_id',
           
    //     )
    //     ->join('class', 'class.id', '=', 'assign_class_teacher.class_id')
    //     ->where('assign_class_teacher.is_delete', '=', 0)
    //     ->where('assign_class_teacher.status', '=', 0)
    //     ->where('assign_class_teacher.teacher_id','=',$teacher_id)
    //     ->groupBy('assign_class_teacher.class_id')
    //     ->get();
    // }

    public static function getMyClassSubjectGroup($teacher_id)
{
    return self::select(
            'assign_class_teacher.*', 
            'class.name as class_name',
            'class.id as class_id'
        )
        ->join('class', 'class.id', '=', 'assign_class_teacher.class_id')
        ->where('assign_class_teacher.is_delete', '=', 0)
        ->where('assign_class_teacher.status', '=', 0)
        ->where('assign_class_teacher.teacher_id', '=', $teacher_id)
        ->groupBy('assign_class_teacher.class_id')
        ->get();
}





}
