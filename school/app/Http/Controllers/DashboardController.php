<?php

namespace App\Http\Controllers;

use App\Models\User;
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
