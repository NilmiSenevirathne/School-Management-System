<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\ParentModel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ParentController extends Controller
{
    public function list()
    {

        $data['getRecord'] = ParentModel::getParent();


        $data['header_title'] ="Parent List";
        return view('admin.parent.list',$data);
    }

    public function add()
    {

        $data['header_title'] ="Add New Parent";
        return view('admin.parent.add',$data);
    }

    public function insert(Request $request)
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'occupation' => 'required|string|max:255',
            'contact' => 'required|numeric|digits_between:10,15',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:0,1', // Assuming 0 for Active, 1 for Inactive
            'email' => 'required|email|unique:parent,email|unique:users,email',
            'password' => 'required', 
        ]);

        $parent = new ParentModel();
        $parent->name = trim($request->name);
        $parent->last_name = trim($request->last_name);
        $parent->address = trim($request->address);
        $parent->gender = trim($request->gender);
        $parent->occupation = trim($request->occupation);
        $parent->contact = trim($request->contact);
       

        if(!empty($request->file('profile_picture')))
        {
            $ext = $request->file('profile_picture')->getClientOriginalExtension();
            $file = $request->file('profile_picture');
            $randomStr = Str::random(20);
            $filename = strtolower($randomStr).'.'.$ext;
            $file->move('uploads/parent/', $filename);

            $parent->profile_picture = $filename;

        }

        $parent->status = trim($request->status);
        $parent->email = trim($request->email);
        $parent->password= Hash::make($request->password);
        $parent->user_type = 4;
        $parent->save();

         // Create and save the corresponding User

         request()->validate([
            'email' => 'required|email|unique:users'
        ]);

         $user = new User;
         $user->name = trim($request->name);
         $user->last_name = trim($request->last_name);
         $user->email = trim($request->email);
         $user->password = Hash::make($request->password);
         $user->user_type = 4; // parent user type for user table
         $user->save();

        return redirect('admin/parent/list')->with('success', 'Parent added successfully' );
    }

     //edit parent
     public function edit($id)
     {
         $data['getRecord'] = ParentModel:: getSingleParent($id);
 
         if(!empty($data['getRecord']))
         {
             
             $data['header_title'] ="Edit Student";
             return view('admin.parent.edit',$data);
 
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
             'occupation' => 'required|string|max:255',
             'contact' => 'required|numeric|digits_between:10,15',
             'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
             'status' => 'required|in:0,1', // Assuming 0 for Active, 1 for Inactive
            // 'email' => 'required|email|unique:parent,email|unique:users,email',
             
         ]);
 
         $parent = ParentModel::findOrFail($id);
         $parent->name = trim($request->name);
         $parent->last_name = trim($request->last_name);
         $parent->address = trim($request->address);
         $parent->gender = trim($request->gender);
         $parent->occupation = trim($request->occupation);
         $parent->contact = trim($request->contact);
        
 
         if(!empty($request->file('profile_picture')))
         {
             if(!empty($parent->getParentProfile()))
             {
               unlink('uploads/parent/'.$parent->profile_picture); // Delete the old profile picture
             }
             $ext = $request->file('profile_picture')->getClientOriginalExtension();
             $file = $request->file('profile_picture');
             $randomStr = Str::random(20);
             $filename = strtolower($randomStr).'.'.$ext;
             $file->move('uploads/parent/', $filename);
 
             $parent->profile_picture = $filename;
 
         }
 
         $parent->status = trim($request->status);
         $parent->email = trim($request->email);
         if(!empty($request->password))
         {
             $parent->password= Hash::make($request->password);
         }
        
         $parent->save();
 
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
 
         return redirect('admin/parent/list')->with('success', 'Parent and User updated successfully' );
     }

     public function delete($email)
     {
         // Find the student record
         $parent = ParentModel::where('email', $email)->first();
     
         if ($parent) {
             // Set the is_delete flag for the student record
             $parent->is_delete = 1;
             $parent->save();
     
             // Find and update the corresponding user record
             $user = User::where('email', $email)->first();
             if ($user) {
                 // Set the is_delete flag for the user record
                 $user->is_delete = 1;
                 $user->save();
             }
     
             return redirect('admin/parent/list')->with('success', 'Parent and corresponding user marked as deleted successfully');
         } else {
             return redirect('admin/parent/list')->with('error', 'Parent not found');
         }
     }

     //my students list

     public function MyStudent($id)
     {
        $data['getParent'] = ParentModel::getSingleParent($id);
        $data['parent_id'] = $id;
        $data['getSearchStudent'] = Student::getSearchStudent();
        $data['getRecord'] = Student::getMyStudent($id);
        $data['header_title'] ="Parent Student List";
        return view('admin.parent.mystudent',$data);
     }

     public function AssignStudentParent($student_id,$parent_id)
     {
        $student = Student::getSingleStudent($student_id);
        $student-> parent_id = $parent_id;
        $student->save();

        return redirect()->back()->with('success', 'Student Assign Successfully');

     }
     
     public function AssignStudentParentDelete($student_id)
     {
        $student = Student::getSingleStudent($student_id);
        $student-> parent_id = null;
        $student->save();

        return redirect()->back()->with('success', 'Student Assign Deleted Successfully');

     }

}
