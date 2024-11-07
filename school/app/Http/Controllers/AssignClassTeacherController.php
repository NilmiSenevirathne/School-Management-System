<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\Teacher;
use App\Models\AssignClassTeacherModel;
use Auth;
use Illuminate\Support\Facades\DB;

class AssignClassTeacherController extends Controller
{
    
   //fetch records
    public function list(Request $request)
{
    $data['header_title'] = "Assign Class Teacher";

    // Call the stored procedure without parameters
    $data['getRecord'] = collect(DB::select('CALL FetchAssignedClasses()'));

    return view('admin.assign_class_teacher.list', $data);
}



    public function add(Request $request)
    {
        $data['getClass'] = ClassModel::getClass();
        $data['getTeacher'] = Teacher::getTeacher();
        $data['header_title'] = "Add Assign Class Teacher";
        return view('admin.assign_class_teacher.add', $data);
    }

    
    //insert new records 
    public function insert(Request $request)
    {
        \Log::info('Insert request data:', $request->all());

        // Validate request data
        $request->validate([
            'class_id' => 'required|integer',
            'teacher_id' => 'required|array',
            'status' => 'required|integer',
        ]);

        // Check for selected teachers
        if (!empty($request->teacher_id) && is_array($request->teacher_id)) {
            foreach ($request->teacher_id as $teacher_id) {
                try {
                    // Call the stored procedure InsertAssignedClassTeacher
                    DB::statement('CALL InsertAssignedClassTeacher(?, ?, ?, ?)', [
                        $request->class_id,
                        $teacher_id,
                        $request->status,
                        Auth::user()->id,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Error assigning class teacher: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'An error occurred while assigning the class teacher. Please try again.');
                }
            }

            return redirect()->route('admin.assign_class_teacher.list')->with('success', 'Assigned Class to Teacher Successfully!');
        } else {
            return redirect()->back()->with('error', 'Please select at least one teacher.');
        }
    }



    public static function edit($id)
    {
        $getRecord = AssignClassTeacherModel::where('id', $id)->where('is_delete', 0)->first();

        if (!empty($getRecord)) {
            $data['getRecord'] = $getRecord;
            $data['getAssignTeacherID'] = AssignClassTeacherModel::getAssignTeacherID($getRecord->class_id);
            $data['getClass'] = ClassModel::getClass();
            $data['getTeacher'] = Teacher::getTeacher();
            $data['header_title'] = 'Edit Assign Class Teacher';
            return view('admin.assign_class_teacher.edit', $data);
        } else {
            abort(404, 'Record not found.');
        }
    }

    public static function update($id, Request $request)
{
    // Validate request data (make sure class_id and teacher_id are valid)
    $request->validate([
        'class_id' => 'required|integer',
        'teacher_id' => 'required|array',
        'status' => 'required|integer',
    ]);

    // First, fetch existing assignments for the class
    $existingAssignments = AssignClassTeacherModel::where('class_id', $request->class_id)
        ->where('is_delete', 0)
        ->get();

    // Create an array to track existing teacher IDs for easy comparison
    $existingTeacherIds = $existingAssignments->pluck('teacher_id')->toArray();

    // Update existing assignments based on the incoming teacher IDs
    foreach ($existingAssignments as $assignment) {
        if (in_array($assignment->teacher_id, $request->teacher_id)) {
            // Teacher is still assigned, update the status
            $assignment->status = $request->status; 
            $assignment->updated_by = Auth::user()->id; 
            $assignment->save();
        } else {
            // Teacher is no longer assigned, you might want to delete or mark as inactive
            // Optionally: $assignment->is_delete = 1; // Uncomment if you want to mark as deleted
            // $assignment->save();
        }
    }

    // Now handle adding new teachers that are not yet assigned
    foreach ($request->teacher_id as $teacher_id) {
        // Only add if not already assigned
        if (!in_array($teacher_id, $existingTeacherIds)) {
            AssignClassTeacherModel::create([
                'class_id' => $request->class_id,
                'teacher_id' => $teacher_id,
                'status' => $request->status,
                'created_by' => Auth::user()->id,
            ]);
        }
    }

    return redirect('admin/assign_class_teacher/list')->with('success', 'Assign To Class Teacher successfully updated!');
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

    //update the single record
    public function update_single(Request $request, $id)
   {
    // Validate the incoming request data
    $request->validate([
        'class_id' => 'required|integer',
        'teacher_id' => 'required|integer',
        'status' => 'required|boolean',
    ]);

    // Call the stored procedure with the necessary parameters
    DB::statement('CALL update_single_assigned_class_teacher(?, ?, ?, ?, ?)', [
        $id, // teacher_assign_id
        $request->class_id, // class_id
        $request->teacher_id, // teacher_id
        $request->status, // status
        Auth::user()->id // updated_by
    ]);

    return redirect('admin/assign_class_teacher/list')->with('success', 'Assign To Class Teacher successfully updated!');
   }


    
    //delete the record 
    // public function delete($id)
    // {
    //      // Call the stored procedure with the assigned class teacher ID
    //      DB::statement('CALL delete_assigned_class_teacher(?)', [$id]);

    //      return redirect()->route('admin.assign_class_teacher.list')->with('success', 'Record deleted successfully.');
    // }
    public function delete($id)
{
    // Call the SQL function with the assigned class teacher ID
    $result = DB::select('SELECT delete_newassigned_class_teacher(?) AS result', [$id]);

    // Check the result of the function
    if ($result[0]->result) {
        return redirect()->route('admin.assign_class_teacher.list')->with('success', 'Record deleted successfully.');
    } else {
        return redirect()->route('admin.assign_class_teacher.list')->with('error', 'Failed to delete record. It may not exist or has already been deleted.');
    }
}



// teacher side work
    public function myClassSubject()
    {
        // Get the logged-in teacher's email
        $email = Auth::user()->email;

        // Fetch the teacher record using the email
        $teacher = DB::table('teacher')->where('email', $email)->first();

        if (!$teacher) {
            // Handle case where no teacher is found with that email
            return redirect()->back()->with('error', 'Teacher not found');
        }

        // Execute the stored procedure and fetch records for the logged-in teacher by teacher ID
        $data['getRecord'] = DB::select('CALL TeacherFetchMyClassAndSubject(?)', [$teacher->id]);
        
        $data['header_title'] = "My Class & Subject";
        return view('teacher.my_class_subject', $data);
    }



}
