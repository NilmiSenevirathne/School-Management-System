<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParentModel extends Model
{
    use HasFactory;

    protected $table = 'parent';

    protected $fillable = [
        'id',
        'name',
        'last_name',
        'address',
        'gender',
        'occupation',
        'contact',
        'status',
        'profile_picture',
        'email ',
        'password',
        'user_type',
        'is_delete',
        'created_at',
    ];

    
    static public function getSingleParent($id)
    {

        return self::find($id);
    }


    static public function getParent()
    {
        $return = self::select('parent.*')
        ->where('is_delete','=',0);
        if(!empty(Request::get('name')))
        {
           $return = $return->where('parent.name','like','%'.Request::get('name').'%');
        }
        if(!empty(Request::get('last_name')))
        {
           $return = $return->where('parent.last_name','like','%'.Request::get('last_name').'%');
        }

         if(!empty(Request::get('email')))
         {
            $return = $return->where('parent.email','like','%'.Request::get('email').'%');
         }
         if (!empty(Request::get('gender'))) {
          $return = $return->where('parent.gender', '=', Request::get('gender'));
      }
       

        $return = $return->orderBy('id','desc')
        ->paginate(20);

        return $return;
    }

    
    public function getParentProfile()
    {
        if(!empty($this->profile_picture) && file_exists('uploads/parent/'.$this->profile_picture))
        {
          return url ('uploads/parent/'.$this->profile_picture);
        }
        else
        {
          return "";
        }
    }
}
