<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\Teacher;
use App\Models\AssignClassTeacherModel;
use Auth;

class AssignClassTeacherController extends Controller
{
    
    public function list(Request $request)
    {
        $data['header_title'] = "Assign Class Teacher";
        $query = AssignClassTeacherModel::with(['class', 'teacher', 'user'])
            ->where('is_delete', 0);
    
        // Apply filters if set
        if ($request->filled('class_name')) {
            $query->whereHas('class', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->class_name . '%');
            });
        }
        if ($request->filled('teacher_name')) {
            $query->whereHas('teacher', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->teacher_name . '%');
            });
        }
        if ($request->filled('status')) {
            // Ensure status is an integer for comparison
            $query->where('status', (int)$request->status);
        }
        if ($request->filled('date')) {
            // Ensure correct date format, e.g., Y-m-d
            try {
                $formattedDate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->date)->format('Y-m-d');
                $query->whereDate('created_at', $formattedDate);
            } catch (\Exception $e) {
                // Optional: Handle invalid date format by ignoring the date filter
                \Log::error('Invalid date format in request: ' . $e->getMessage());
            }
        }
    
        $data['getRecord'] = $query->get();
        return view('admin.assign_class_teacher.list', $data);
    }
    


    public function add(Request $request)
    {
        $data['getClass'] = ClassModel::getClass();
        $data['getTeacher'] = Teacher::getTeacher();
        $data['header_title'] = "Add Assign Class Teacher";
        return view('admin.assign_class_teacher.add', $data);
    }

    public function insert(Request $request)
    {
        \Log::info('Insert request data:', $request->all());

        // Validate request data
        $request->validate([
            'class_id' => 'required|integer',
            'teacher_id' => 'required|array',
            'status' => 'required|integer',
        ]);

        if (!empty($request->teacher_id) && is_array($request->teacher_id)) {
            foreach ($request->teacher_id as $teacher_id) {
                $existingAssignment = AssignClassTeacherModel::where('class_id', $request->class_id)
                    ->where('teacher_id', $teacher_id)
                    ->first();

                try {
                    if ($existingAssignment) {
                        // Update existing record
                        $existingAssignment->status = $request->status; 
                        $existingAssignment->updated_by = Auth::user()->id; 
                        $existingAssignment->save();
                    } else {
                        // Create new assignment
                        AssignClassTeacherModel::create([
                            'class_id' => $request->class_id,
                            'teacher_id' => $teacher_id,
                            'status' => $request->status,
                            'created_by' => Auth::user()->id,
                        ]);
                    }
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

    public function update_single(Request $request, $id)
    {
        // Find the existing assignment by ID
        $existingAssignment = AssignClassTeacherModel::find($id);
        
        if (!$existingAssignment) {
            return redirect()->back()->with('error', 'Record not found.');
        }
    
        // Update the fields
        $existingAssignment->class_id = $request->class_id; // Update class_id
        $existingAssignment->teacher_id = $request->teacher_id; // Update teacher_id
        $existingAssignment->status = $request->status; // Update status
        $existingAssignment->updated_by = Auth::user()->id; // Set the user who updated
        $existingAssignment->save(); 
    
        return redirect('admin/assign_class_teacher/list')->with('success', 'Assign To Class Teacher successfully updated!');
    }
    

    public function delete($id)
    {
        $record = AssignClassTeacherModel::findOrFail($id);
        $record->is_delete = 1; // Soft delete
        $record->save();

        return redirect()->route('admin.assign_class_teacher.list')->with('success', 'Record deleted successfully.');
    }

    // teacher side work
    public function myClassSubject()
{
    $data['getRecord']= AssignClassTeacherModel::getMyClassSubject(Auth::user()->id);
    $data['header_title'] = "My Class & Subject";
    return view('teacher.my_class_subject', $data);
}

}
