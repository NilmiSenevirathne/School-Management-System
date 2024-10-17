<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\User;

class AttendanceController extends Controller
{
    public function AttendanceStudent(Request $request){ 
        $data['getClass'] = ClassModel::getClass();

        if(!empty($request->get('class_id')) && !empty($request->get('attendance_date'))){

            $data['getStudent'] = User::getStudentClass($request->get('class_id'));
        }

        $data['header_title'] = "Student Attendance";
        return view('admin.attendance.student',$data);
    }
}
