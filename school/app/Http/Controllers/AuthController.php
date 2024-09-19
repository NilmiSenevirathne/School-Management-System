<?php

namespace App\Http\Controllers;

use Hash;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login()
    {
       // dd(Hash::make(123456));

       if(!empty(Auth::check()))// Check if the user is already authenticated
      {
         // If the user is authenticated, redirect them to the dashboard
         if(Auth::user()->user_type == 1){
            return redirect('admin/dashboard');
        }
        else if(Auth::user()->user_type == 2){
            return redirect('teacher/dashboard');
        }
        else if(Auth::user()->user_type == 3){
            return redirect('student/dashboard');
        }
        else if(Auth::user()->user_type == 4){
            return redirect('parent/dashboard');
        }
       }
        return view ('auth.login');    // If the user is not authenticated, show the login page

    }

    public function AuthLogin(Request $request)
    {
        $reminder = !empty($request->reminder) ? true : false;
       if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password],true))
       {
        if(Auth::user()->user_type == 1){
            return redirect('admin/dashboard');
        }
        else if(Auth::user()->user_type == 2){
            return redirect('teacher/dashboard');
        }
        else if(Auth::user()->user_type == 3){
            return redirect('student/dashboard');
        }
        else if(Auth::user()->user_type == 4){
            return redirect('parent/dashboard');
        }
         
       }
       else{
        return redirect()->back()->with('error','Please enter correct email and password');
       }
    }

    public function forgotpassword()
    {
        return view ('auth.forgot');    // If the user is not authenticated, show the login page

    }
    public function PostForgotPassword(Request $request)
    {

        $user = User::getEmailSingle($request->email);
        if(!empty($user))
        {
           $user->remember_token =Str::random(30);
           $user->save();
           Mail::to($user->email)->send(new ForgotPasswordMail($user));

           return redirect()->back()->with('success','Please check your email and reset your password');


        }
        else{
            return redirect()->back()->with('error','No User Found with this email');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect(url(''));
    }


}
