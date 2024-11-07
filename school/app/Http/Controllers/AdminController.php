<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

    

    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact' => 'required|numeric|digits_between:10,15',
            'email' => 'required|email|unique:admin|unique:users',
            'password' => 'required|string|min:6',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048' // Profile picture validation
        ]);
    
        // Handle profile picture upload
        $profilePictureName = null;
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $extension = $file->getClientOriginalExtension();
            $profilePictureName = Str::random(20) . '.' . $extension;
            $file->move(public_path('uploads/admin'), $profilePictureName);
        }
    
        $passwordHash = Hash::make($request->password);
    
        // Execute the stored procedure
        DB::statement('CALL InsertAdminAndUser(?, ?, ?, ?, ?, ?, ?, ?)', [
            $request->name,
            $request->last_name,
            $request->address,
            $request->contact,
            $request->email,
            $passwordHash,
            1, // Admin user type
            $profilePictureName
        ]);
    
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
           // 'email' => 'required|email|unique:users,email,' . $id . ',id|unique:admin,email,' . $id . ',id',
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

        if(!empty($request->file('profile_picture')))
        {
            if(!empty($admin->getAdminProfile()))
            {
              unlink('uploads/admin/'.$admin->profile_picture); // Delete the old profile picture
            }
            $ext = $request->file('profile_picture')->getClientOriginalExtension();
            $file = $request->file('profile_picture');
            $randomStr = Str::random(20);
            $filename = strtolower($randomStr).'.'.$ext;
            $file->move('uploads/admin/', $filename);

            $admin->profile_picture = $filename;

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

public function AdminAccount()
{
    // Get the current logged-in user's email
    $email = Auth::user()->email;

    // Fetch the admin's details from the teachers table based on the email
    $data['getRecord'] = Admin::getAdminAccount($email);

    // Add any additional data if needed
    $data['header_title'] = "My Account";

    // Return the view with the fetched data
    return view('admin.myaccount', $data);
}

public function UpdateAdminAccount(Request $request)
{
    $email = Auth::user()->email; // Get logged-in user's email

    request()->validate([
        'name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'contact' => 'required|numeric|digits_between:10,15',
       // 'email' => 'required|email|unique:users,email,' . $id . ',id|unique:admin,email,' . $id . ',id',
    ]);
   

    // Update Admin record
    $admin = Admin::where('email', $email)->firstOrFail();
    $admin->name = trim($request->name);
    $admin->last_name = trim($request->last_name);
    $admin->address = trim($request->address);
    $admin->email = trim($request->email);
   
    $admin->save();

    // Find the corresponding User record based on the email
    $user = User::where('email', $request->old_email)->first();
    if ($user) {
        // Update User record with the same details as Admin
        $user->name = trim($request->name);
        $user->last_name = trim($request->last_name);
        $user->email = trim($request->email);
        
        $user->save();
    }

    return redirect()->back()->with('success', 'Account updated successfully');

}
}




