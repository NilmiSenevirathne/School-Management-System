<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;
    protected $table = 'student';
    protected $fillable = [
        'id',
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

    static public function getSingleStudent($id)
    {

        return self::find($id);
    }

     //get student account information
  
public static function getStudentAccount($email)
{
    return self::where('email', $email)->first();
}


    static public function getStudent()
    {
        $return = self::select('student.*','class.name as class_name')
        ->join('class','class.id','=','student.class_id','left')
        ->where('student.is_delete','=',0);

        if(!empty(Request::get('name')))
        {
           $return = $return->where('student.name','like','%'.Request::get('name').'%');
        }
        if(!empty(Request::get('last_name')))
        {
           $return = $return->where('student.last_name','like','%'.Request::get('last_name').'%');
        }

         if(!empty(Request::get('email')))
         {
            $return = $return->where('student.email','like','%'.Request::get('email').'%');
         }
         if(!empty(Request::get('class')))
         {
            $return = $return->where('class.name','like','%'.Request::get('class').'%');
         }
         if (!empty(Request::get('gender'))) {
            $return = $return->where('student.gender', '=', Request::get('gender'));
        }
        
         
         if(!empty(Request::get('admission_date')))
         {
            $return = $return->whereDate('student.admission_date','=',Request::get('admission_date'));
         }

        $return = $return->orderBy('student.id','desc')
        ->paginate(20);

        return $return;
    }

    public function getStudentProfile()
    {
        if(!empty($this->profile_picture) && file_exists('uploads/profile/'.$this->profile_picture))
        {
          return url ('uploads/profile/'.$this->profile_picture);
        }
        else
        {
          return "";
        }
    }
}
