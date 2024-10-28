<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;
    protected $table = 'exam';
    protected $fillable = [
        'id',
        'name',
        'note',
        'created_by',
        'is_delete',
        'created_at',
    ];

    static public function getSingle($id)
    {
        return self::find($id);
    }

    static public function getExam()
    {
        return self::select('exam.*')
        ->join('users','users.id','=','exam.created_by')
        ->where('exam.is_delete', '=', 0)
        ->orderBy('exam.name','asc')
        ->get();
    }
}
