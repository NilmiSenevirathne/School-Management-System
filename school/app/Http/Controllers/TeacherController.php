<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function list()
    {

        $data['getRecord'] = Teacher::getTeacher();


        $data['header_title'] ="Teacher List";
        return view('admin.teacher.list',$data);
    }

    public function add()
    {

        $data['header_title'] ="Add New Teacher";
        return view('admin.teacher.add',$data);
    }

    public function insert(Request $request)
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date|before:today',
            'date_of_birth' => 'required',
            'contact' => 'required|numeric|digits_between:10,15',
            'qualification' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'note' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:0,1', // Assuming 0 for Active, 1 for Inactive
            'email' => 'required|email|unique:teacher,email|unique:users,email',
            'password' => 'required', 
        ]);

        $teacher = new Teacher();
        $teacher->name = trim($request->name);
        $teacher->last_name = trim($request->last_name);
        $teacher->address = trim($request->address);
        $teacher->gender = trim($request->gender);

        if(!empty($request->date_of_birth))
        {
            $teacher->date_of_birth = trim($request->date_of_birth);

        }
    
     
        if(!empty($request->date_of_join))
        {
            $teacher->date_of_join= trim($request->date_of_join);

        }
        $teacher->contact = trim($request->contact);

        if(!empty($request->file('profile_picture')))
        {
            $ext = $request->file('profile_picture')->getClientOriginalExtension();
            $file = $request->file('profile_picture');
            $randomStr = Str::random(20);
            $filename = strtolower($randomStr).'.'.$ext;
            $file->move('uploads/teacher/', $filename);

            $teacher->profile_picture = $filename;

        }



        $teacher->qualification = trim($request->qualification);
        $teacher->experience = trim($request->experience);
        $teacher->note = trim($request->note);


        $teacher->status = trim($request->status);
        $teacher->email = trim($request->email);
        $teacher->password= Hash::make($request->password);
        $teacher->user_type = 2;
        $teacher->save();

         // Create and save the corresponding User

         request()->validate([
            'email' => 'required|email|unique:users'
        ]);

         $user = new User;
         $user->name = trim($request->name);
         $user->last_name = trim($request->last_name);
         $user->email = trim($request->email);
         $user->password = Hash::make($request->password);
         $user->user_type = 2; // teacher user type for user table
         $user->save();

        return redirect('admin/teacher/list')->with('success', 'Teacher added successfully' );
    }

    public function edit($id)
    {
        $data['getRecord'] = Teacher::getSingleTeacher($id);

        if(!empty($data['getRecord']))
        {
            $data['header_title'] ="Edit Admin";
            return view('admin.teacher.edit',$data);

        }
        else{
            abort(404);
        }
      
    }

    public function update(Request $request,$id)
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date|before:today',
            'date_of_join' => 'required',
            'contact' => 'required|numeric|digits_between:10,15',
            'qualification' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'note' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:0,1', // Assuming 0 for Active, 1 for Inactive
           // 'email' => 'required|email|unique:teacher,email|unique:users,email',
            
        ]);

        $teacher = Teacher::findOrFail($id);
        $teacher->name = trim($request->name);
        $teacher->last_name = trim($request->last_name);
        $teacher->address = trim($request->address);
        $teacher->gender = trim($request->gender);

        if(!empty($request->date_of_birth))
        {
            $teacher->date_of_birth = trim($request->date_of_birth);

        }
    
     
        if(!empty($request->date_of_join))
        {
            $teacher->date_of_join= trim($request->date_of_join);

        }
        $teacher->contact = trim($request->contact);
        if(!empty($request->file('profile_picture')))
        {
            if(!empty($teacher->getTeacherProfile()))
            {
              unlink('uploads/teacher/'.$teacher->profile_picture); // Delete the old profile picture
            }
            $ext = $request->file('profile_picture')->getClientOriginalExtension();
            $file = $request->file('profile_picture');
            $randomStr = Str::random(20);
            $filename = strtolower($randomStr).'.'.$ext;
            $file->move('uploads/teacher/', $filename);

            $teacher->profile_picture = $filename;

        }




        $teacher->qualification = trim($request->qualification);
        $teacher->experience = trim($request->experience);
        $teacher->note = trim($request->note);


        $teacher->status = trim($request->status);
        $teacher->email = trim($request->email);

        if(!empty($request->password))
        {
            $teacher->password= Hash::make($request->password);
        }
        $teacher->save();

         // Create and save the corresponding User


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

        return redirect('admin/teacher/list')->with('success', 'Teacher Updated successfully' );
    }

    public function delete($email)
     {
         // Find the teacher record
         $teacher = Teacher::where('email', $email)->first();
     
         if ($teacher) {
             // Set the is_delete flag for the teacher record
             $teacher->is_delete = 1;
             $teacher->save();
     
             // Find and update the corresponding user record
             $user = User::where('email', $email)->first();
             if ($user) {
                 // Set the is_delete flag for the user record
                 $user->is_delete = 1;
                 $user->save();
             }
     
             return redirect('admin/teacher/list')->with('success', 'Teacher and corresponding user marked as deleted successfully');
         } else {
             return redirect('admin/teacher/list')->with('error', 'Teacher not found');
         }
     }

     public function TeacherAccount()
     {
         // Get the current logged-in user's email
         $email = Auth::user()->email;
     
         // Fetch the teacher's details from the teachers table based on the email
         $data['getRecord'] = Teacher::getTeacherAccount($email);
     
         // Add any additional data if needed
         $data['header_title'] = "My Account";
     
         // Return the view with the fetched data
         return view('teacher.myaccount', $data);
     }

     public function UpdateTeacherAccount(Request $request)
{
    $email = Auth::user()->email; // Get logged-in user's email
    
    request()->validate([
        'name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'gender' => 'required|in:Male,Female',
        'date_of_birth' => 'required|date|before:today', 
        'contact' => 'required|numeric|digits_between:10,15',
        'qualification' => 'required|string|max:255',
        'experience' => 'required|string|max:255',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Fetch the teacher's record based on their email
    $teacher = Teacher::where('email', $email)->firstOrFail();
    
    // Update the teacher's details
    $teacher->name = trim($request->name);
    $teacher->last_name = trim($request->last_name);
    $teacher->address = trim($request->address);
    $teacher->gender = trim($request->gender);
    $teacher->date_of_birth = trim($request->date_of_birth);
    $teacher->contact = trim($request->contact);
    
    // Update profile picture if uploaded
    if (!empty($request->file('profile_picture'))) {
        if (!empty($teacher->profile_picture)) {
            // Delete the old profile picture
            unlink('uploads/teacher/' . $teacher->profile_picture);
        }
        $ext = $request->file('profile_picture')->getClientOriginalExtension();
        $randomStr = Str::random(20);
        $filename = strtolower($randomStr) . '.' . $ext;
        $request->file('profile_picture')->move('uploads/teacher/', $filename);

        $teacher->profile_picture = $filename;
    }

    $teacher->qualification = trim($request->qualification);
    $teacher->experience = trim($request->experience);
    $teacher->email = trim($request->email);

    $teacher->save();

    // Update the corresponding User record based on the email
    $user = User::where('email', $email)->first();
    if ($user) {
        $user->name = trim($request->name);
        $user->last_name = trim($request->last_name);
        $user->email = trim($request->email);
        $user->save();
    }

    return redirect()->back()->with('success', 'Account updated successfully');
}




}
      



