<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ClassSubjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AuthController::class, 'login']);
Route::post('login', [AuthController::class, 'AuthLogin']);
Route::get('logout', [AuthController::class, 'logout']);
Route::get('forgot-password', [AuthController::class, 'forgotpassword']);
Route::post('forgot-password', [AuthController::class, 'PostForgotPassword']);

Route::group(['middleware' => 'admin'], function () {

    Route::get('admin/dashboard', [DashboardController::class, 'dashboard']);
    Route::get('admin/admin/list', [AdminController::class, 'list']);
    Route::get('admin/admin/add', [AdminController::class, 'add']);
    Route::post('admin/admin/add', [AdminController::class, 'insert']);
    Route::get('admin/admin/edit/{id}', [AdminController::class, 'edit']);
    Route::post('admin/admin/edit/{id}', [AdminController::class, 'update']);
    Route::get('admin/admin/delete/{email}', [AdminController::class, 'delete']);

    //teacher Routes

    Route::get('admin/teacher/list', [TeacherController::class, 'list']);
    Route::get('admin/teacher/add', [TeacherController::class, 'add']);
    Route::post('admin/teacher/add', [TeacherController::class, 'insert']);
    Route::get('admin/teacher/edit/{id}', [TeacherController::class, 'edit']);
    Route::post('admin/teacher/edit/{id}', [TeacherController::class, 'update']);
    Route::get('admin/teacher/delete/{email}', [TeacherController::class, 'delete']);

    //student Routes
    Route::get('admin/student/list', [StudentController::class, 'list']);
    Route::get('admin/student/add', [StudentController::class, 'add']);
    Route::post('admin/student/add', [StudentController::class, 'insert']);
    Route::get('admin/student/edit/{id}', [StudentController::class, 'edit']);
    Route::post('admin/student/edit/{id}', [StudentController::class, 'update']);
    Route::get('admin/student/delete/{email}', [StudentController::class, 'delete']);

    //parent Routes

    Route::get('admin/parent/list', [ParentController::class, 'list']);
    Route::get('admin/parent/add', [ParentController::class, 'add']);
    Route::post('admin/parent/add', [ParentController::class, 'insert']);
    Route::get('admin/parent/edit/{id}', [ParentController::class, 'edit']);
    Route::post('admin/parent/edit/{id}', [ParentController::class, 'update']);
    Route::get('admin/parent/delete/{email}', [ParentController::class, 'delete']);

    Route::get('admin/parent/my-student/{id}', [ParentController::class, 'MyStudent']);
    Route::get('admin/parent/assign_student_parent/{student_id}/{parent_id}', [ParentController::class, 'AssignStudentParent']);
    Route::get('admin/parent/assign_student_parent_delete/{student_id}', [ParentController::class, 'AssignStudentParentDelete']);

    //class Routes

    Route::get('admin/class/list', [ClassController::class, 'list']);
    Route::get('admin/class/add', [ClassController::class, 'add']);
    Route::post('admin/class/add', [ClassController::class, 'insert']);
    Route::get('admin/class/edit/{id}', [ClassController::class, 'edit']);
    Route::post('admin/class/edit/{id}', [ClassController::class, 'update']);
    Route::get('admin/class/delete/{id}', [ClassController::class, 'delete']);

    //subject Routes

    Route::get('admin/subject/list', [SubjectController::class, 'list']);
    Route::get('admin/subject/add', [SubjectController::class, 'add']);
    Route::post('admin/subject/add', [SubjectController::class, 'insert']);
    Route::get('admin/subject/edit/{id}', [SubjectController::class, 'edit']);
    Route::post('admin/subject/edit/{id}', [SubjectController::class, 'update']);
    Route::get('admin/subject/delete/{id}', [SubjectController::class, 'delete']);

    //assign-subject

    Route::get('admin/assign-subject/list', [ClassSubjectController::class, 'list']);
    Route::get('admin/assign-subject/add', [ClassSubjectController::class, 'add']);
    Route::post('admin/assign-subject/add', [ClassSubjectController::class, 'insert']);
    Route::get('admin/assign-subject/edit/{id}', [ClassSubjectController::class, 'edit']);
    Route::post('admin/assign-subject/edit/{id}', [ClassSubjectController::class, 'update']);
    Route::get('admin/assign-subject/delete/{id}', [ClassSubjectController::class, 'delete']);

    Route::get('admin/assign-subject/edit_single/{id}', [ClassSubjectController::class, 'edit_single']);
    Route::post('admin/assign-subject/edit_single/{id}', [ClassSubjectController::class, 'update_single']);

    //my aacount
    Route::get('admin/account', [AdminController::class, 'AdminAccount']);
    Route::post('admin/account', [AdminController::class, 'UpdateAdminAccount']);
    //change password

    Route::get('admin/change-password', [UserController::class, 'changePassword']);
    Route::post('admin/change-password', [UserController::class, 'UpdatePassword']);

});

Route::group(['middleware' => 'teacher'], function () {

    Route::get('teacher/dashboard', [DashboardController::class, 'dashboard']);

    Route::get('teacher/change-password', [UserController::class, 'changePassword']);
    Route::post('teacher/change-password', [UserController::class, 'UpdatePassword']);

    Route::get('teacher/account', [TeacherController::class, 'TeacherAccount']);
    Route::post('teacher/account', [TeacherController::class, 'UpdateTeacherAccount']);

});

Route::group(['middleware' => 'student'], function () {

    Route::get('student/dashboard', [DashboardController::class, 'dashboard']);

    Route::get('student/change-password', [UserController::class, 'changePassword']);
    Route::post('student/change-password', [UserController::class, 'UpdatePassword']);

    Route::get('student/my_subject', [SubjectController::class, 'MySubject']);

    Route::get('student/account', [StudentController::class, 'studentAccount']);
    Route::post('student/account', [StudentController::class, 'UpdateStudentAccount']);

});

Route::group(['middleware' => 'parent'], function () {

    Route::get('parent/dashboard', [DashboardController::class, 'dashboard']);

    Route::get('parent/change-password', [UserController::class, 'changePassword']);
    Route::post('parent/change-password', [UserController::class, 'UpdatePassword']);

    Route::get('parent/account', [ParentController::class, 'parentAccount']);
    Route::post('parent/account', [ParentController::class, 'UpdateParentAccount']);

    Route::get('parent/my_student/subject/{student_id}', [SubjectController::class, 'ParentStudentSubject']);

    Route::get('parent/my_student', [ParentController::class, 'myStudentParent']);

});
