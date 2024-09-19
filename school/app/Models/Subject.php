<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subject';

    static public function getSingle($id)
    {

        return self::find($id);
    }

    static public function getRecord()
    {
        $return = Subject::select('subject.*','users.name as created_by_name')
          ->join('users','users.id','subject.created_by');
          if(!empty(Request::get('name')))
          {
            $return =$return->where('subject.name', 'like' ,'%'.Request::get('name').'%');

          }
          if(!empty(Request::get('type')))
          {
            $return =$return->where('subject.type', '=',Request::get('type'));

          }
          if(!empty(Request::get('date')))
          {
             $return = $return->whereDate('subject.created_at','=',Request::get('date'));
          }
           $return =$return->where('subject.is_delete', '=' ,0)
          ->orderBY('subject.id','desc')
          ->paginate(20);

        return $return;
    }

    static public function getSubject()
    {
      $return = Subject::select('subject.*')
      ->join('users','users.id','subject.created_by')
      ->where('subject.is_delete', '=' ,0)
      ->where('subject.status', '=' ,0)
      ->orderBY('subject.name','asc')
      ->get();

    return $return;

    }

    
}
