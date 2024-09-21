<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function list()
    {

        $data['getRecord'] = Admin::getAdmin();


        $data['header_title'] ="Admin List";
        return view('admin.admin.list',$data);
    }

    public function add()
    {


        $data['header_title'] ="Add New Admin";
        return view('admin.admin.add',$data);
    }

    // public function insert(Request $request)
    // {

    //     request()->validate([
    //         'email' => 'required|email|unique:users'
    //     ]);

    //    $user = new User;
    //    $user->name = trim($request->name);
    //    $user->email = trim($request->email);
    //    $user->password = Hash::make($request->password);
    //    $user->user_type = 1;

    //    $user->save();
       
    //    return redirect ('admin/admin/list')->with('success','Admin added successfully');
    // }

    public function insert(Request $request)
    {
        // Validate email for the admin table
        request()->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact' => 'required|numeric|digits_between:10,15',
            'email' => 'required|email|unique:admin'
        ]);
    
        // Create and save the Admin
        $admin = new Admin;
        $admin->name = trim($request->name);
        $admin->last_name = trim($request->last_name);
        $admin->address = trim($request->address);
        $admin->contact = trim($request->contact);
        $admin->email = trim($request->email);
        $admin->password = Hash::make($request->password);
        $admin->user_type = 1; // Admin user type
        $admin->save();
    
        // Validate email for the user table
        request()->validate([
            'email' => 'required|email|unique:users'
        ]);
    
        // Create and save the corresponding User
        $user = new User;
        $user->name = trim($request->name);
        $user->last_name = trim($request->last_name);
        $user->email = trim($request->email);
        $user->password = Hash::make($request->password);
        $user->user_type = 1; // Admin user type for user table
        $user->save();
    
        return redirect('admin/admin/list')->with('success', 'Admin added successfully');
    }
    

    public function edit($id)
    {
        $data['getRecord'] = Admin::getSingleAdmin($id);

        if(!empty($data['getRecord']))
        {
            $data['header_title'] ="Edit Admin";
            return view('admin.admin.edit',$data);

        }
        else{
            abort(404);
        }
      
    }

    public function update(Request $request, $id)
    {
        // Validate email for both tables
        request()->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact' => 'required|numeric|digits_between:10,15',
            'email' => 'required|email|unique:users,email,' . $id . ',id|unique:admin,email,' . $id . ',id',
        ]);
       

        // Update Admin record
        $admin = Admin::findOrFail($id);
        $admin->name = trim($request->name);
        $admin->last_name = trim($request->last_name);
        $admin->address = trim($request->address);
        $admin->email = trim($request->email);
        if (!empty($request->password)) {
            $admin->password = Hash::make($request->password);
        }
        $admin->save();

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

        return redirect('admin/admin/list')->with('success', 'Admin and User updated successfully');
    }


public function delete($email)
{
    // Find the admin record
    $admin = Admin::where('email', $email)->first();

    if ($admin) {
        // Set the is_delete flag for the admin record
        $admin->is_delete = 1;
        $admin->save();

        // Find and update the corresponding user record
        $user = User::where('email', $email)->first();
        if ($user) {
            // Set the is_delete flag for the user record
            $user->is_delete = 1;
            $user->save();
        }

        return redirect('admin/admin/list')->with('success', 'Admin and corresponding user marked as deleted successfully');
    } else {
        return redirect('admin/admin/list')->with('error', 'Admin not found');
    }
}


}
