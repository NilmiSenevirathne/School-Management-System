<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\Teacher;
use App\Models\AssignClassTeacherModel;
use Auth;

class AssignClassTeacherController extends Controller
{
    public function list(Request $request){

        $data['getRecord'] = AssignClassTeacherModel::getRecord();
        $data['header_title'] = "Assign Class Teacher";
        return view('admin.assign_class_teacher.list',$data);
    }

    public function add(Request $request){
        $data['getClass'] = ClassModel::getClass();
        $data['getTeacher'] = Teacher::getTeacher();
        $data['header_title'] = "Add Assign Class Teacher";
        return view('admin.assign_class_teacher.add',$data);
        
    }

    
    public function insert(Request $request)
    {
        // Log the incoming request data for debugging
        \Log::info('Insert request data:', $request->all());

        // Validate request data
        $request->validate([
            'class_id' => 'required|integer',
            'teacher_id' => 'required|array',
            'status' => 'required|integer', // Changed to integer for status
        ]);

        // Check if teacher_id is provided and is an array
        if (!empty($request->teacher_id) && is_array($request->teacher_id)) {
            foreach ($request->teacher_id as $teacher_id) {
                // Check if the teacher is already assigned to the class
                $existingAssignment = AssignClassTeacherModel::where('class_id', $request->class_id)
                    ->where('teacher_id', $teacher_id)
                    ->first();

                try {
                    if ($existingAssignment) {
                        // Update the existing record if found
                        $existingAssignment->status = $request->status;
                        $existingAssignment->updated_by = Auth::user()->id; // Log who updated
                        $existingAssignment->save();
                    } else {
                        // Create a new assignment if not already present
                        AssignClassTeacherModel::create([
                            'class_id' => $request->class_id,
                            'teacher_id' => $teacher_id,
                            'status' => $request->status,
                            'created_by' => Auth::user()->id, // Corrected from Auth::teacher()->id
                        ]);
                    }
                } catch (\Exception $e) {
                    // Log the exception and redirect back with an error message
                    \Log::error('Error assigning class teacher: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'An error occurred while assigning the class teacher. Please try again.');
                }
            }

            // Redirect to the list with a success message
            return redirect()->route('admin.assign_class_teacher.list')->with('success', 'Assigned Class to Teacher Successfully!');
        } else {
            // Redirect back with an error message if teacher_id is empty or not an array
            return redirect()->back()->with('error', 'Please select at least one teacher.');
        }
    }
    

    

}
