<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard(){

        $data['header_title'] = 'Dashboard';
        if (Auth::user()->user_type == 1) {
            $data['TotalAdmin'] = DB::select('SELECT getUserCount(1) AS count')[0]->count;
            $data['TotalTeacher'] = DB::select('SELECT getUserCount(2) AS count')[0]->count;
            $data['TotalStudent'] = DB::select('SELECT getUserCount(3) AS count')[0]->count;
            $data['TotalParent'] = DB::select('SELECT getUserCount(4) AS count')[0]->count;
    
            return view('admin.dashboard', $data);
        }
        else if(Auth::user()->user_type == 2){

            $email = Auth::user()->email;
            $teacher = DB::table('teacher')->where('email', $email)->first();
    
            if (!$teacher) {
                return redirect()->back()->with('error', 'Teacher not found');
            }
    
            $data['TotalStudent'] = DB::select('SELECT getTeacherStudentCount(?) AS count', [$teacher->id])[0]->count;
            $data['TotalSubject'] = DB::select('SELECT getTeacherSubjectCount(?) AS count', [$teacher->id])[0]->count;
            $data['TotalClass'] = DB::select('SELECT getTeacherClassCount(?) AS count', [$teacher->id])[0]->count;

             
            return view('teacher.dashboard',$data);
        }
        else if(Auth::user()->user_type == 3){
            return view('student.dashboard',$data);
        }
        else if(Auth::user()->user_type == 4){
            return view('parent.dashboard',$data);
        }
    }
}
