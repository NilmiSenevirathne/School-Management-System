<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\Subject; 
use App\Models\WeekModel; 
use App\Models\ClassSubjectTimetableModel;
use Illuminate\Support\Facades\DB;

class ClassTimetableController extends Controller
{
    // Display the class timetable list
    
    public function list(Request $request)
{
    $data['getClass'] = ClassModel::all();
    $data['getSubject'] = Subject::all();

    $class_id = $request->class_id;
    $subject_id = $request->subject_id;

    // Call the stored procedure
    $getWeek = DB::select('CALL admin_show_classtimetable(?, ?)', [$class_id, $subject_id]);

    $week = [];
    foreach ($getWeek as $value) {
        $dataW = array();
        $dataW['week_id'] = $value->week_id;
        $dataW['week_name'] = $value->week_name;
        $dataW['start_time'] = $value->start_time;
        $dataW['end_time'] = $value->end_time;
        $dataW['room_number'] = $value->room_number;
        $week[] = $dataW;
    }

    $data['week'] = $week;
    $data['header_title'] = "Class Timetable List";

    // Pass selected class_id and subject_id to the view for consistency after search
    $data['selected_class_id'] = $request->class_id;
    $data['selected_subject_id'] = $request->subject_id;

    return view('admin.class_timetable.list', $data);
}



    // Handle AJAX request to get subjects by class ID
    public function get_subject(Request $request)
    {
        $class_id = $request->class_id;
        
        // Fetch subjects based on class_id (adjust the query as necessary)
        $subjects = Subject::where('class_id', $class_id)->get();
        
        // Return JSON response for AJAX
        return response()->json($subjects);
    }

    public function insert_update(Request $request)
    {

        ClassSubjectTimetableModel::where('class_id','=', $request->class_id)->where('subject_id','=', $request->subject_id)->delete();
       foreach ($request->timetable as $timetable) {
         if (!empty($timetable['week_id']) && !empty($timetable['start_time']) && !empty($timetable['end_time']) && !empty($timetable['room_number'])) {
              $save = new ClassSubjectTimetableModel;
             $save->class_id = $request->class_id;
             $save->subject_id = $request->subject_id;
             $save->week_id = $timetable['week_id'];
             $save->start_time = $timetable['start_time'];
            $save->end_time = $timetable['end_time'];
            $save->room_number = $timetable['room_number'];
            $save->save();
        }
    }

    // Corrected this line
    return redirect()->back()->with('success', 'Class Timetable Successfully Saved!');
   }




}