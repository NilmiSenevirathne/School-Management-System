<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\Subject; 
use App\Models\WeekModel; 
use App\Models\ClassSubjectTimetableModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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


//student side
// public function MyTimetable(Request $request)
// {
//     // Get the logged-in student's email
//     $email = Auth::user()->email;

//     // Fetch the student record to get the student's class_id
//     $student = \DB::table('student')->where('email', $email)->first();

//     // Ensure the student record exists
//     if (!$student) {
//         return redirect()->route('login')->with('error', 'Student not found');
//     }

//     // Fetch the list of weeks
//     $getWeek = WeekModel::getRecord();

//     // Initialize the timetable array
//     $week = [];

//     // Loop through each week to populate timetable details for the logged-in student's specific class
//     foreach ($getWeek as $value) {
//         $dataW = [];
//         $dataW['week_id'] = $value->id;
//         $dataW['week_name'] = $value->name;

//         // Fetch timetable records from the class_subject_timetable table based on the student's class_id
//         $timetableEntry = \DB::table('class_subject_timetable')
//             ->where('class_id', $student->class_id)
//             ->where('week_id', $value->id)
//             ->select('subject_id', 'start_time', 'end_time', 'room_number')
//             ->get();

//         // Check if there are any timetable entries for the class
//         if ($timetableEntry->isNotEmpty()) {
//             // Loop through each timetable entry and add it to the week's data
//             $timetableData = [];
//             foreach ($timetableEntry as $entry) {
//                 // Fetch subject name from the subject table using the subject_id
//                 $subjectName = \DB::table('subject')
//                     ->where('id', $entry->subject_id)
//                     ->value('name');

//                 // Populate the timetable details for each subject in the week
//                 $timetableData[] = [
//                     'subject_name' => $subjectName,
//                     'start_time' => $entry->start_time,
//                     'end_time' => $entry->end_time,
//                     'room_number' => $entry->room_number,
//                 ];
//             }

//             // Assign the week's timetable data
//             $dataW['timetable'] = $timetableData;
//         } else {
//             // Set empty values if no timetable entries found for this week
//             $dataW['timetable'] = [];
//         }

//         // Add the week's data to the main timetable array
//         $week[] = $dataW;
//     }

//     // Prepare the data to be passed to the view
//     $data['week'] = $week;
//     $data['header_title'] = "My Timetable";

//     // Return the view with the timetable data
//     return view('student.my_timetable', $data);
// }

public function MyTimetable(Request $request)
{
    // Get the logged-in student's email
    $email = Auth::user()->email;

    // Call the stored procedure StudentFetchMyClassTimetable with the student's email
    $timetableData = \DB::select('CALL StudentFetchMyClassTimetable(?)', [$email]);

    // Check if the procedure returned a message indicating no class_id
    if (isset($timetableData[0]->message) && $timetableData[0]->message === 'Student not found or not assigned to a class') {
        return redirect()->route('login')->with('error', 'Student not found or not assigned to a class');
    }

    // Initialize an empty array to structure the timetable by week
    $weekData = [];
    
    // Group timetable data by week_id and week_name
    foreach ($timetableData as $entry) {
        $weekId = $entry->week_id;
        $weekName = $entry->week_name;

        // Ensure the week_id key exists in the array
        if (!isset($weekData[$weekId])) {
            $weekData[$weekId] = [
                'week_id' => $weekId,
                'week_name' => $weekName,
                'timetable' => [],
            ];
        }

        // Append timetable entry to the respective week's timetable
        $weekData[$weekId]['timetable'][] = [
            'subject_name' => $entry->subject_name,
            'start_time' => $entry->start_time,
            'end_time' => $entry->end_time,
            'room_number' => $entry->room_number,
        ];
    }

    // Convert associative array to an indexed array
    $week = array_values($weekData);

    // Prepare the data to be passed to the view
    $data['week'] = $week;
    $data['header_title'] = "My Timetable";

    // Return the view with the timetable data
    return view('student.my_timetable', $data);
}


}