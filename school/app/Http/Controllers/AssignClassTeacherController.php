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


    public static function edit($id)
   {
    $getRecord = AssignClassTeacherModel::where('id', $id)->where('is_delete', 0)->first();  // Ensure not deleted
    
    if (!empty($getRecord)) {
        $data['getRecord'] = $getRecord;
        $data['getAssignTeacherID'] = AssignClassTeacherModel::getAssignTeacherID($getRecord->class_id);
        $data['getClass'] = ClassModel::getClass();
        $data['getTeacher'] = Teacher::getTeacher();
        $data['header_title'] = 'Edit Assign Class Teacher';
        return view('admin.assign_class_teacher.edit', $data);
    } else {
        // Return a 404 error if no record is found
        abort(404, 'Record not found.');
    }
  }

  public static function update($id, Request $request)
{
    
    // Your logic to update the assign class teacher records
    AssignClassTeacherModel::deleteTeacher($request->class_id);

    if (!empty($request->teacher_id)) {
        foreach ($request->teacher_id as $teacher_id) {
            $getAlreadyFirst = AssignClassTeacherModel::getAlreadyFirst($request->class_id, $teacher_id);
            if (!empty($getAlreadyFirst)) {
                $getAlreadyFirst->status = $request->status;
                $getAlreadyFirst->save();
            } else {
                $save = new AssignClassTeacherModel;
                $save->class_id = $request->class_id;
                $save->teacher_id = $teacher_id;
                $save->status = $request->status;
                $save->created_by = Auth::user()->id;
                $save->save();
            }
        }
    }
    
    return redirect('admin/assign_class_teacher/list')->with('success', 'Assign To Class Teacher successfully updated !');
}

public function edit_single($id)  
{
    $getRecord = AssignClassTeacherModel::where('id', $id)->where('is_delete', 0)->first();
    
    if (!empty($getRecord)) {
        $data['getRecord'] = $getRecord;
        $data['getClass'] = ClassModel::getClass();
        $data['getTeacher'] = Teacher::getTeacher();
        $data['header_title'] = 'Edit Assign Class Teacher';
        return view('admin.assign_class_teacher.edit_single', $data);
    } else {
        abort(404, 'Record not found.');
    }
}


public function update_single(Request $request, $id)
{
    
    $getAlreadyFirst = AssignClassTeacherModel::where('class_id', $request->class_id)
        ->where('teacher_id', $request->teacher_id)
        ->first();
    
    if (!empty($getAlreadyFirst)) {
        
        $getAlreadyFirst->status = $request->status;
        $getAlreadyFirst->save();

        return redirect('admin/assign_class_teacher/list')->with('success', 'Status successfully Updated');
    } else {
        
        $save = AssignClassTeacherModel::find($id); 
        if ($save) {
            $save->class_id = $request->class_id;
            $save->teacher_id = $request->teacher_id;
            $save->status = $request->status;
            $save->save();

            return redirect('admin/assign_class_teacher/list')->with('success', 'Assign Class Teacher successfully Updated');
        } else {
            return redirect('admin/assign_class_teacher/list')->with('error', 'Record not found.');
        }
    }
}


public function delete($id)
{
    // Attempt to retrieve the record from the AssignClassTeacherModel
    $record = AssignClassTeacherModel::where('id', $id)->where('is_delete', 0)->first();
    
    // Check if the record was found
    if ($record) {
        // Mark the record as deleted
        $record->is_delete = 1;
        $record->save();

        return redirect()->back()->with('success', 'Record Deleted successfully!');
    } else {
        // If the record is not found or already deleted, redirect with an error message
        return redirect()->back()->with('error', 'Record not found or already deleted.'); 
    }
}

//function side work
public function MyClassSubject()
    {
        $data['getRecord']= AssignClassTeacherModel::getMyClassSubject(Auth::User()->id);
        $data['header_title'] = "My Class & Subject";
        return view('teacher.my_class_subject', $data);
    }


}