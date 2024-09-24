<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teacher';
    protected $fillable = [
        'id',
        'name',
        'last_name',
        'address',
        'gender',
        'date_of_birth',
        'date_of_join',
        'contact',
        'qualification',
        'experience',
        'note',
        'status',
        'profile_picture',
        'email ',
        'password',
        'user_type',
        'is_delete',
        'created_at',
    ];

    static public function getSingleTeacher($id)
    {

        return self::find($id);
    }


  //get Teacher account information
   // Teacher.php (Model)
public static function getTeacherAccount($email)
{
    return self::where('email', $email)->first();
}


    static public function getTeacher()
    {
        $return = self::select('teacher.*')
        ->where('is_delete','=',0);
        if(!empty(Request::get('name')))
        {
           $return = $return->where('teacher.name','like','%'.Request::get('name').'%');
        }
        if(!empty(Request::get('last_name')))
        {
           $return = $return->where('teacher.last_name','like','%'.Request::get('last_name').'%');
        }

         if(!empty(Request::get('email')))
         {
            $return = $return->where('teacher.email','like','%'.Request::get('email').'%');
         }
         if (!empty(Request::get('gender'))) {
            $return = $return->where('teacher.gender', '=', Request::get('gender'));
        }
        if (!empty(Request::get('status'))) {
            $return = $return->where('teacher.status', '=', Request::get('status'));
        }

        $return = $return->orderBy('id','desc')
        ->paginate(20);

        return $return;
    }

    public function getTeacherProfile()
    {
        if(!empty($this->profile_picture) && file_exists('uploads/teacher/'.$this->profile_picture))
        {
          return url ('uploads/teacher/'.$this->profile_picture);
        }
        else
        {
          return "";
        }
    }
}
