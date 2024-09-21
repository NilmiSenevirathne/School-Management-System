<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\ClassModel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function list()
    {

        $data['getRecord'] = Student::getStudent();


        $data['header_title'] ="Student List";
        return view('admin.student.list',$data);
    }

    
    public function add()
    {

        $data['getClass'] = ClassModel::getClass();
        $data['header_title'] ="Add New Student";
        return view('admin.student.add',$data);
    }

    public function insert(Request $request)
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'admission_number' => 'required|string|max:20|unique:student,admission_number',
            'roll_number' => 'required|string|max:20|unique:student,roll_number',
            'class_id' => 'required',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date|before:today',
            'contact' => 'required|numeric|digits_between:10,15',
            'admission_date' => 'required|date|before_or_equal:today',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:0,1', // Assuming 0 for Active, 1 for Inactive
            'email' => 'required|email|unique:student,email|unique:users,email',
            'password' => 'required', 
        ]);

        $student = new Student();
        $student->name = trim($request->name);
        $student->last_name = trim($request->last_name);
        $student->address = trim($request->address);
        $student->admission_number = trim($request->admission_number);
        $student->roll_number= trim($request->roll_number);
        $student->class_id = trim($request->class_id);
        $student->gender = trim($request->gender);

        if(!empty($request->date_of_birth))
        {
            $student->date_of_birth = trim($request->date_of_birth);

        }
    
        $student->contact = trim($request->contact);
        if(!empty($request->admission_date))
        {
            $student->admission_date = trim($request->admission_date);

        }

        if(!empty($request->file('profile_picture')))
        {
            $ext = $request->file('profile_picture')->getClientOriginalExtension();
            $file = $request->file('profile_picture');
            $randomStr = Str::random(20);
            $filename = strtolower($randomStr).'.'.$ext;
            $file->move('uploads/profile/', $filename);

            $student->profile_picture = $filename;

        }



        $student->status = trim($request->status);
        $student->email = trim($request->email);
        $student->password= Hash::make($request->password);
        $student->user_type = 3;
        $student->save();

         // Create and save the corresponding User

         request()->validate([
            'email' => 'required|email|unique:users'
        ]);

         $user = new User;
         $user->name = trim($request->name);
         $user->last_name = trim($request->last_name);
         $user->email = trim($request->email);
         $user->password = Hash::make($request->password);
         $user->user_type = 3; // student user type for user table
         $user->save();

        return redirect('admin/student/list')->with('success', 'Student added successfully' );
    }

    //edit student
    public function edit($id)
    {
        $data['getRecord'] = Student:: getSingleStudent($id);

        if(!empty($data['getRecord']))
        {
            $data['getClass'] = ClassModel::getClass();
            $data['header_title'] ="Edit Student";
            return view('admin.student.edit',$data);

        }
        else{
            abort(404);
        }
      
    }

    public function update(Request $request,$id){

         // Validate email for both tables
         request()->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'admission_number' => 'required|string|max:20',
            'roll_number' => 'required|string|max:20',
            'class_id' => 'required',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date|before:today',
            'contact' => 'required|numeric|digits_between:10,15',
            'admission_date' => 'required|date|before_or_equal:today',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:0,1', // Assuming 0 for Active, 1 for Inactive
            'email' => 'required|email|unique:student,email|unique:users,email',
             
        ]);
        //update student record
        $student = Student::findOrFail($id);
        $student->name = trim($request->name);
        $student->last_name = trim($request->last_name);
        $student->address = trim($request->address);
        $student->admission_number = trim($request->admission_number);
        $student->roll_number= trim($request->roll_number);
        $student->class_id = trim($request->class_id);
        $student->gender = trim($request->gender);

        if(!empty($request->date_of_birth))
        {
            $student->date_of_birth = trim($request->date_of_birth);

        }
    
        $student->contact = trim($request->contact);
        if(!empty($request->admission_date))
        {
            $student->admission_date = trim($request->admission_date);

        }

        if(!empty($request->file('profile_picture')))
        {
            if(!empty($student->getStudentProfile()))
            {
              unlink('uploads/profile/'.$student->profile_picture); // Delete the old profile picture
            }
            $ext = $request->file('profile_picture')->getClientOriginalExtension();
            $file = $request->file('profile_picture');
            $randomStr = Str::random(20);
            $filename = strtolower($randomStr).'.'.$ext;
            $file->move('uploads/profile/', $filename);

            $student->profile_picture = $filename;

        }



        $student->status = trim($request->status);
        $student->email = trim($request->email);

        if(!empty($request->password))
        {
            $student->password= Hash::make($request->password);
        }
       
        $student->save();

          // Find the corresponding User record based on the email
          $user = User::where('email', $request->old_email)->first();
          if ($user) {
              // Update User record with the same details as Admin
              $user->name = trim($request->name);
              $user->last_name = trim($request->last_name);
              $user->email = trim($request->email);
              if (!empty($request->password)) {
                  $user->password = Hash::make($request->password);
              }
              $user->save();
          }
  
          return redirect('admin/student/list')->with('success', 'Student and User updated successfully');

       
    }
    public function delete($email)
{
    // Find the student record
    $student = Student::where('email', $email)->first();

    if ($student) {
        // Set the is_delete flag for the student record
        $student->is_delete = 1;
        $student->save();

        // Find and update the corresponding user record
        $user = User::where('email', $email)->first();
        if ($user) {
            // Set the is_delete flag for the user record
            $user->is_delete = 1;
            $user->save();
        }

        return redirect('admin/student/list')->with('success', 'Student and corresponding user marked as deleted successfully');
    } else {
        return redirect('admin/admin/list')->with('error', 'Student not found');
    }
}

}
