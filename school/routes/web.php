<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassSubjectController;
use App\Http\Controllers\ExaminationsController;
use App\Http\Controllers\AssignClassTeacherController;
use App\Http\Controllers\ClassTimetableController;
use App\Http\Controllers\HomeworkController;

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

    // class-timetable
    Route::get('admin/class_timetable/list', [ClassTimetableController::class, 'list']);
    Route::post('admin/class_timetable/get_subject', [ClassTimetableController::class, 'get_subject']);
    Route::post('admin/class_timetable/add', [ClassTimetableController::class, 'insert_update']);
    
    //my aacount
    Route::get('admin/account', [AdminController::class, 'AdminAccount']);
    Route::post('admin/account', [AdminController::class, 'UpdateAdminAccount']);
    //change password

    Route::get('admin/change-password', [UserController::class, 'changePassword']);
    Route::post('admin/change-password', [UserController::class, 'UpdatePassword']);



    //assign-class teacher
    Route::get('admin/assign_class_teacher/list', [AssignClassTeacherController::class, 'list'])
    ->name('admin.assign_class_teacher.list'); // Named route for listing

    Route::get('admin/assign_class_teacher/add', [AssignClassTeacherController::class, 'add'])
    ->name('admin.assign_class_teacher.add'); // Named route for adding

    Route::post('admin/assign_class_teacher/add', [AssignClassTeacherController::class, 'insert'])
    ->name('admin.assign_class_teacher.insert');

    Route::get('admin/assign_class_teacher/edit/{id}', [AssignClassTeacherController::class, 'edit'])
    ->name('admin.assign_class_teacher.edit');

    Route::post('admin/assign_class_teacher/edit/{id}', [AssignClassTeacherController::class, 'update'])
    ->name('admin.assign_class_teacher.update');

    Route::get('admin/assign_class_teacher/edit_single/{id}', [AssignClassTeacherController::class, 'edit_single'])
    ->name('admin.assign_class_teacher.edit_single');

    Route::post('admin/assign_class_teacher/edit_single/{id}', [AssignClassTeacherController::class, 'update_single'])
    ->name('admin.assign_class_teacher.update_single');

    Route::delete('admin/assign_class_teacher/delete/{id}', [AssignClassTeacherController::class, 'delete'])
    ->name('admin.assign_class_teacher.delete');

    //Attendance
    Route::get('admin/attendance/student', [AttendanceController::class, 'AttendanceStudent']);
    Route::post('admin/attendance/student/save', [AttendanceController::class, 'AttendanceStudentSubmit']);
    Route::get('admin/attendance/report', [AttendanceController::class, 'AttendanceReport']);


    
     //Examination

     Route::get('admin/examinations/exam/list', [ExaminationsController::class, 'exam_list']);
     Route::get('admin/examinations/exam/add', [ExaminationsController::class, 'exam_add']);
     Route::post('admin/examinations/exam/add', [ExaminationsController::class, 'exam_insert']);
     Route::get('admin/examinations/exam/edit/{id}', [ExaminationsController::class, 'exam_edit']);
     Route::post('admin/examinations/exam/edit/{id}', [ExaminationsController::class, 'exam_update']);
     Route::get('admin/examinations/exam/delete/{id}', [ExaminationsController::class, 'exam_delete']);


     //exam schedule
     Route::get('admin/examinations/exam_schedule', [ExaminationsController::class, 'exam_schedule']);
     Route::post('admin/examinations/exam_schedule_insert', [ExaminationsController::class, 'exam_schedule_insert']);

     Route::get('admin/examinations/marks_register', [ExaminationsController::class, 'marks_register']);
     Route::post('admin/examinations/submit_marks_register', [ExaminationsController::class, 'submit_marks_register']);
     Route::post('admin/examinations/single_submit_marks_register', [ExaminationsController::class, 'single_submit_marks_register']);

     Route::get('admin/examinations/marks_grade', [ExaminationsController::class, 'marks_grade']);
    Route::get('admin/examinations/marks_grade/add', [ExaminationsController::class, 'marks_grade_add']);
    Route::post('admin/examinations/marks_grade/add', [ExaminationsController::class, 'marks_grade_insert']);
    Route::get('admin/examinations/marks_grade/edit/{id}', [ExaminationsController::class, 'marks_grade_edit']);
    Route::post('admin/examinations/marks_grade/edit/{id}', [ExaminationsController::class, 'marks_grade_update']);
    Route::get('admin/examinations/marks_grade/delete/{id}', [ExaminationsController::class, 'marks_grade_delete']);

    //Homework
    Route::get('admin/homework/homework', [HomeworkController::class, 'homework']);
    //Route::get('homework', [HomeworkController::class, 'homeworks']);
    Route::get('admin/homework/homework/add', [HomeworkController::class, 'add']);
    Route::post('admin/ajax_get_subject', [HomeworkController::class, 'ajax_get_subject']);
    Route::post('admin/homework/homework/add', [HomeworkController::class, 'insert']);
    //Route::post('admin/homework/homework/add', [HomeworkController::class, 'store'])->name('homework.add');
    Route::get('admin/homework/homework/edit/{id}', [HomeworkController::class, 'edit']);
    Route::post('admin/homework/homework/edit/{id}', [HomeworkController::class, 'update']);
    Route::get('admin/homework/homework/delete/{id}', [HomeworkController::class, 'delete']);
    Route::get('admin/homework/homework/submitted/{id}', [HomeworkController::class, 'submitted']);





});

Route::group(['middleware' => 'teacher'], function () {

    Route::get('teacher/dashboard', [DashboardController::class, 'dashboard']);

    Route::get('teacher/change-password', [UserController::class, 'changePassword']);
    Route::post('teacher/change-password', [UserController::class, 'UpdatePassword']);

    Route::get('teacher/account', [TeacherController::class, 'TeacherAccount']);
    Route::post('teacher/account', [TeacherController::class, 'UpdateTeacherAccount']);

    
    Route::get('teacher/my_student', [StudentController::class, 'MyStudent'])->middleware('auth')->name('teacher.my_student');

    Route::get('teacher/my_class_subject', [AssignClassTeacherController::class, 'MyClassSubject']);
    Route::get('teacher/my_class_subject/class_timetable/{class_id}/{subject_id}', [ClassTimetableController::class, 'MyTimetableTeacher']);


    Route::get('teacher/marks_register', [ExaminationsController::class, 'marks_register_teacher']);
    Route::post('teacher/submit_marks_register', [ExaminationsController::class, 'submit_marks_register']);
    Route::post('teacher/single_submit_marks_register', [ExaminationsController::class, 'single_submit_marks_register']);

    Route::get('teacher/attendance/student', [AttendanceController::class, 'AttendanceStudentTeacher']);
    Route::post('teacher/attendance/student/save', [AttendanceController::class, 'AttendanceStudentSubmit']);
    Route::get('teacher/attendance/report', [AttendanceController::class, 'AttendanceReportTeacher']);


    Route::get('teacher/homework/homework', [HomeworkController::class, 'HomeworkTeacher']);
    Route::get('teacher/homework/homework/add', [HomeworkController::class, 'addTeacher']);
    Route::post('teacher/ajax_get_subject', [HomeworkController::class, 'ajax_get_subject']);
    Route::post('teacher/homework/homework/add', [HomeworkController::class, 'insertTeacher']);
    Route::get('teacher/homework/homework/edit/{id}', [HomeworkController::class, 'editTeacher']);
    //Route::post('teacher/homework/homework/edit/{id}', [HomeworkController::class, 'updateTeacher']);
    Route::put('teacher/homework/homework/edit/{id}', [HomeworkController::class, 'updateTeacher']);
    Route::get('teacher/homework/homework/delete/{id}', [HomeworkController::class, 'delete']);
    Route::get('teacher/homework/homework/submitted/{id}', [HomeworkController::class, 'submittedTeacher']);






});

Route::group(['middleware' => 'student'], function () {

    Route::get('student/dashboard', [DashboardController::class, 'dashboard']);

    Route::get('student/change-password', [UserController::class, 'changePassword']);
    Route::post('student/change-password', [UserController::class, 'UpdatePassword']);

    Route::get('student/my_subject', [SubjectController::class, 'MySubject']);
    Route::get('student/my_timetable',[ClassTimetableController::class, 'MyTimetable']);
    Route::get('student/my_examtimetable',[ExaminationsController::class, 'MyExamTimetable']);


    Route::get('student/account', [StudentController::class, 'studentAccount']);
    Route::post('student/account', [StudentController::class, 'UpdateStudentAccount']);

    Route::get('student/my_exam_result', [ExaminationsController::class, 'MyExamResult']);


    Route::get('student/my_homework', [HomeworkController::class, 'HomeworkStudent']);
    Route::get('student/my_homework/submit_homework/{id}', [HomeworkController::class, 'SubmitHomework']);
    Route::post('student/my_homework/submit_homework/{id}', [HomeworkController::class, 'SubmitHomeworkInsert']);

    Route::get('student/my_submitted_homework', [HomeworkController::class, 'HomewarkSubmitedStudent']);



});

Route::group(['middleware' => 'parent'], function () {

    Route::get('parent/dashboard', [DashboardController::class, 'dashboard']);

    Route::get('parent/change-password', [UserController::class, 'changePassword']);
    Route::post('parent/change-password', [UserController::class, 'UpdatePassword']);

    Route::get('parent/account', [ParentController::class, 'parentAccount']);
    Route::post('parent/account', [ParentController::class, 'UpdateParentAccount']);

    Route::get('parent/my_student/subject/{student_id}', [SubjectController::class, 'ParentStudentSubject']);

    Route::get('parent/my_student', [ParentController::class, 'myStudentParent']);
    Route::get('parent/my_student/exam_result/{student_id}', [ExaminationsController::class, 'MyExamResultParent']);


});
