<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignClassTeacherModel extends Model
{
    use HasFactory;
    protected $table = 'assign_class_teacher';
    protected $fillable = ['class_id', 'teacher_id', 'status', 'created_by', 'is_delete'];

    public static function getAlreadyFirst($class_id, $teacher_id)
    {
        return self::where('class_id', '=', $class_id)->where('teacher_id', '=', $teacher_id)->first();
    }

    //method to get records
   public static function getRecord()
{
    $return = self::select(
            'assign_class_teacher.*', 
            'class.name as class_name', 
            'teacher.name as teacher_name', // Selecting teacher name
            'users.name as created_by_name'
        )
        ->join('class', 'class.id', '=', 'assign_class_teacher.class_id')
        ->join('teacher', 'teacher.id', '=', 'assign_class_teacher.teacher_id') // Ensure this join is correct
        ->join('users', 'users.id', '=', 'assign_class_teacher.created_by')
        ->where('assign_class_teacher.is_delete', '=', 0);

    $return = $return->orderBy('assign_class_teacher.id', 'desc')
        ->paginate(10);

    return $return;
}

    
}
