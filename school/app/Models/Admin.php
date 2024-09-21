<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    use HasFactory;
    protected $table = 'admin';
    protected $fillable = [
        'id',
        'name',
        'last_name',
        'address',
        'contact',
        'email ',
        'password',
        'user_type',
        'is_delete',
        'created_at',
    ];

    static public function getSingleAdmin($id)
    {

        return self::find($id);
    }

    static public function getAdmin()
    {
        $return = self::select('admin.*')
        ->where('is_delete','=',0);
        if(!empty(Request::get('name')))
        {
           $return = $return->where('name','like','%'.Request::get('name').'%');
        }
        if(!empty(Request::get('last_name')))
        {
           $return = $return->where('last_name','like','%'.Request::get('last_name').'%');
        }

         if(!empty(Request::get('email')))
         {
            $return = $return->where('email','like','%'.Request::get('email').'%');
         }
         if(!empty(Request::get('date')))
         {
            $return = $return->whereDate('created_at','=',Request::get('date'));
         }

        $return = $return->orderBy('id','desc')
        ->paginate(20);

        return $return;
    }

}
