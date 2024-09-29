<?php

namespace App\Http\Controllers;

use App\Models\ClassSubject;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function list()
    {
        $data['getRecord'] = Subject::getRecord();

        $data['header_title'] = 'Subject List';

        return view('admin.subject.list', $data);
    }

    public function add()
    {
        $data['header_title'] = 'Add Subject';

        return view('admin.subject.add', $data);
    }

    public static function insert(Request $request)
    {
        $save = new Subject;
        $save->name = trim($request->name);
        $save->type = trim($request->type);
        $save->status = trim($request->status);
        $save->created_by = Auth::user()->id;
        $save->save();

        return redirect('admin/subject/list')->with('success', 'Subject added successfully');

    }

    public function edit($id)
    {
        $data['getRecord'] = Subject::getSingle($id);
        if (! empty($data['getRecord'])) {

            $data['header_title'] = 'Edit Subject';

            return view('admin.subject.edit', $data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request, $id)
    {

        $save = Subject::getSingle($id);
        $save->name = trim($request->name);
        $save->type = trim($request->type);
        $save->status = trim($request->status);
        $save->save();

        return redirect('admin/subject/list')->with('success', 'Subject updated successfully');
    }

    public function delete($id)
    {

        $save = Subject::getSingle($id);
        $save->is_delete = 1;
        $save->save();

        return redirect()->back()->with('success', 'Subject deleted successfully');

    }

    //Student side

    public function MySubject()
    {
        // Get the logged-in student's email
        $email = Auth::user()->email;

        // Fetch the class_id from the student table using the email
        $student = \DB::table('student')->where('email', $email)->first();

        if ($student) {
            $class_id = $student->class_id;

            // Fetch subjects using the model method
            $data['getRecord'] = ClassSubject::MySubject($class_id);

            $data['header_title'] = 'My Subjects';

            return view('student.my_subject', $data);
        } else {
            return redirect()->back()->with('error', 'No student record found for this email.');
        }
    }

    // Parent side

    // public function ParentStudentSubject($student_id)
    // {
    //     // Get the logged-in student's email
    //     $email = Auth::user()->email;

    //     // Fetch the class_id from the student table using the email
    //     $student = \DB::table('student')->where('email', $email)->first();

    //     if ($student) {
    //         $class_id = $student->class_id;
    //         $data['student_id'] = $student_id;

    //         // Fetch subjects using the model method
    //         $data['getRecord'] = ClassSubject::MySubject($class_id);

    //         $data['header_title'] = 'Student Subjects';

    //         return view('parent.my_student_subject', $data);
    //     } else {
    //         return redirect()->back()->with('error', 'No student record found for this email.');
    //     }
    // }

    public function ParentStudentSubject($student_id)
    {
        // Fetch the student record using the student_id from the URL
        $student = \DB::table('student')->where('id', $student_id)->first();

        if ($student) {
            $class_id = $student->class_id;
            $data['student'] = $student; // Pass the student object to the view
            $data['student_id'] = $student_id;

            // Fetch subjects using the model method
            $data['getRecord'] = ClassSubject::MySubject($class_id);

            $data['header_title'] = 'Student Subjects';

            return view('parent.my_student_subject', $data);
        } else {
            return redirect()->back()->with('error', 'No student record found for this ID.');
        }
    }
}
