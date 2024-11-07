<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\ClassSubject;
use App\Models\HomeworkModel;
use App\Models\AssignClassTeacherModel;
use App\Models\HomeworkSubmitModel;
use Auth;
use Str;
use Illuminate\Support\Facades\DB;



class HomeworkController extends Controller
{

    public function Homework()
    {
    
        $data['getRecord'] = HomeworkModel::getRecord();
        $data['header_title'] = 'homework';
        return view('admin.homework.list', $data); 
    }

    public function add()
    {
        $data['getClass'] = ClassModel::getClass();
        $data['header_title'] = 'Add New Homework';
        return view('admin.homework.add', $data); 
    }
    

// public function storeHomework(Request $request)
// {
//     DB::statement('CALL AddHomework(?, ?, ?, ?, ?, ?, ?)', [
//         $request->class_id,
//         $request->subject_id,
//         $request->homework_date,
//         $request->submission_date,
//         $request->document_file,
//         $request->description,
//         $request->created_by,
//     ]);

//     return redirect()->back()->with('success', 'Homework added successfully.');
// }

public function storeHomework(Request $request)
{
    // Handle file upload if a file is present
    $filename = null;
    if ($request->hasFile('document_file')) {
        $file = $request->file('document_file');
        $filename = date('Ymdhis') . Str::random(20) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/homework'), $filename);
    }

    // Now call the stored procedure with the filename
    DB::statement('CALL AddHomework(?, ?, ?, ?, ?, ?, ?)', [
        $request->class_id,
        $request->subject_id,
        $request->homework_date,
        $request->submission_date,
        $filename, // Pass the uploaded file name here
        $request->description,
        $request->created_by,
    ]);

    return redirect()->back()->with('success', 'Homework added successfully.');
}




    // public function insert(Request $request)
    // {
    //     $homework = new HomeworkModel;
    //     $homework->class_id = trim($request->class_id);
    //     $homework->subject_id = trim($request->subject_id);
    //     $homework->homework_date = trim($request->homework_date);
    //     $homework->submission_date = trim($request->submission_date);
    //     $homework->description = strip_tags(trim($request->description));
    //     $homework->created_by = Auth::user()->id;
    
    //     // Check if a file is uploaded and handle saving
    //     if ($request->hasFile('document_file')) {
    //         $file = $request->file('document_file');
    //         $filename = date('Ymdhis') . Str::random(20) . '.' . $file->getClientOriginalExtension();
    //         $file->move(public_path('uploads/homework'), $filename);
    
    //         $homework->document_file = $filename;
    //     }
    
    //     $homework->save();
    //     return redirect('admin/homework/homework')->with('success', "Homework successfully created");
    // }
    

// public function insert(Request $request)
// {
//     $filename = null;
//     if ($request->hasFile('document_file')) {
//         $file = $request->file('document_file');
//         $filename = date('Ymdhis') . Str::random(20) . '.' . $file->getClientOriginalExtension();
//         $file->move(public_path('uploads/homework'), $filename);
//     }

//     DB::statement('CALL InsertHomework(?, ?, ?, ?, ?, ?, ?)', [
//         trim($request->class_id),
//         trim($request->subject_id),
//         trim($request->homework_date),
//         trim($request->submission_date),
//         strip_tags(trim($request->description)),
//         $filename,
//         Auth::user()->id
//     ]);

//     return redirect('admin/homework/homework')->with('success', "Homework successfully created");
// }

public function insert(Request $request)
{
    $filename = null;
    if ($request->hasFile('document_file')) {
        $file = $request->file('document_file');
        $filename = date('Ymdhis') . Str::random(20) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/homework'), $filename);
    }

    DB::statement('CALL InsertHomework(?, ?, ?, ?, ?, ?, ?)', [
        trim($request->class_id),
        trim($request->subject_id),
        trim($request->homework_date),
        trim($request->submission_date),
        strip_tags(trim($request->description)),
        $filename, // Pass the file name
        Auth::user()->id
    ]);

    return redirect('admin/homework/homework')->with('success', "Homework successfully created");
}



    
    


    public function ajax_get_subject(Request $request)
    {
        $class_id = $request->class_id;
        $getSubject = ClassSubject::MySubject($class_id);
        $html = '';
        $html.= '<option value="">Select Subject</option>';
        foreach($getSubject as $value)
        {
            $html.='<option value="'.$value->subject_id.'">'.$value->subject_name.'</option>';
        }
        
        $json['success'] = $html;
        echo json_encode($json);

    }

    public function edit($id)
    {
        $getRecord = HomeworkModel::getSingle($id);
        $data['getRecord'] = $getRecord;
        $data['getSubject'] = ClassSubject::MySubject($getRecord->class_id);
        $data['getClass'] = ClassModel::getClass();
        $data['header_title'] = 'Edit Homework';
        return view('admin.homework.edit', $data); 
    }

    // public function update(Request $request, $id)
    // {
    //     $homework = HomeworkModel::getSingle($id);
    //     $homework->class_id = trim($request->class_id);
    //     $homework->subject_id = trim($request->subject_id);
    //     $homework->homework_date = trim($request->homework_date);
    //     $homework->submission_date = trim($request->submission_date);
    //     $homework->description = trim($request->description);
        

    //     if(!empty($request->file('document_file')))
    //     {
    //         $ext = $request->file('document_file')->getClientOriginalExtension();
    //         $file = $request->file('document_file');
    //         $randomStr = date('Ymdhis').Str::random(20);
    //         $filename = strtolower($randomStr).'.'.$ext;
    //         $file->move('uploads/homework/', $filename);

    //         $homework->document_file = $filename;

    //     }




    //     $homework->save();

    //     return redirect('admin/homework/homework')->with('success',"Homework successfully updated");
    // } 
//     public function update(Request $request, $id)
// {
//     $homework = HomeworkModel::getSingle($id);
    
//     $homework->class_id = trim($request->class_id);
//     $homework->subject_id = trim($request->subject_id);
//     $homework->homework_date = trim($request->homework_date);
//     $homework->submission_date = trim($request->submission_date);
//     $homework->description = trim($request->description);
    
//     // Handle file upload if a new document is uploaded
//     $documentFileName = null;
//     if (!empty($request->file('document_file'))) {
//         $ext = $request->file('document_file')->getClientOriginalExtension();
//         $file = $request->file('document_file');
//         $randomStr = date('Ymdhis').Str::random(20);
//         $documentFileName = strtolower($randomStr) . '.' . $ext;
//         $file->move('uploads/homework/', $documentFileName);
//     }
    
//     // Call the stored procedure
//     DB::select('CALL update_homework(?, ?, ?, ?, ?, ?, ?)', [
//         $id,
//         $homework->class_id,
//         $homework->subject_id,
//         $homework->homework_date,
//         $homework->submission_date,
//         $homework->description,
//         $documentFileName
//     ]);

//     return redirect('admin/homework/homework')->with('success', "Homework successfully updated");
// }

public function update(Request $request, $id)
{
    $homework = HomeworkModel::getSingle($id);

    $homework->class_id = trim($request->class_id);
    $homework->subject_id = trim($request->subject_id);
    $homework->homework_date = trim($request->homework_date);
    $homework->submission_date = trim($request->submission_date);
    $homework->description = trim($request->description);

    // Handle file upload if a new document is uploaded
    $documentFileName = $homework->document_file;  // Keep existing file by default
    if ($request->hasFile('document_file')) {
        // Upload new file if present
        $ext = $request->file('document_file')->getClientOriginalExtension();
        $file = $request->file('document_file');
        $randomStr = date('Ymdhis') . Str::random(20);
        $documentFileName = strtolower($randomStr) . '.' . $ext;
        $file->move('uploads/homework/', $documentFileName);
    }

    // Call the stored procedure
    DB::select('CALL update_homework(?, ?, ?, ?, ?, ?, ?)', [
        $id,
        $homework->class_id,
        $homework->subject_id,
        $homework->homework_date,
        $homework->submission_date,
        $homework->description,
        $documentFileName // Use the updated file name
    ]);

    return redirect('admin/homework/homework')->with('success', "Homework successfully updated");
}



    // public function delete($id)
    // {
    //     $homework = HomeworkModel::getSingle($id);
    //     $homework->is_delete = 1;
    //     $homework->save();

    //     return redirect()->back()->with('success',"Homework successfully deleted");
    // }
    public function delete($id)
{
    DB::select('CALL delete_homework(?)', [$id]);

    return redirect()->back()->with('success', "Homework successfully deleted");
}


    // public function submitted($homework_id)
    // {
    //     $homework =   
    // }


    //teacher side

    public function HomeworkTeacher()
    {
    
        $data['getRecord'] = HomeworkModel::getRecord();
        $data['header_title'] = 'homework';
        return view('teacher.homework.list', $data); 
    }

    public function addTeacher()
    {
         // Get the logged-in student's email
         $email = Auth::user()->email;

         // Fetch the class_id from the student table using the email
         $teacher = \DB::table('teacher')->where('email', $email)->first();
         if ($teacher) {
            $class_id = $teacher->id;

            $data['getClass'] = AssignClassTeacherModel::getMyClassSubjectGroup($class_id);
            $data['header_title'] = 'Add New Homework';
            return view('teacher.homework.add', $data); 
        }
        else {
            return redirect()->back()->with('error', 'No student record found for this email.');
        }
    }
//     public function addTeacher()
// {
//     // Get the logged-in teacher's email
//     $email = Auth::user()->email;

//     // Call the stored procedure
//     $result = DB::select('CALL add_teacher_procedure(?)', [$email]);

//     // Check the result
//     if (isset($result[0]->error_message)) {
//         // If the procedure returned an error message, show it
//         return redirect()->back()->with('error', $result[0]->error_message);
//     }

//     // If teacher found, proceed with the class and subject details
//     $data['getClass'] = $result;
//     $data['header_title'] = 'Add New Homework';
    
//     return view('teacher.homework.add', $data);
// }


    // public function insertTeacher(Request $request)
    // {
    //     $homework = new HomeworkModel;
    //     $homework->class_id = trim($request->class_id);
    //     $homework->subject_id = trim($request->subject_id);
    //     $homework->homework_date = trim($request->homework_date);
    //     $homework->submission_date = trim($request->submission_date);
    //     $homework->description = strip_tags(trim($request->description));
    //     $homework->created_by = Auth::user()->id;
    
    //     // Check if a file is uploaded and handle saving
    //     if ($request->hasFile('document_file')) {
    //         $file = $request->file('document_file');
    //         $filename = date('Ymdhis') . Str::random(20) . '.' . $file->getClientOriginalExtension();
    //         $file->move(public_path('uploads/homework'), $filename);
    
    //         $homework->document_file = $filename;
    //     }
    
    //     $homework->save();
    //     return redirect('teacher/homework/homework')->with('success', "Homework successfully created");
    // }

    

public function insertTeacher(Request $request)
{
    // Prepare the values from the request
    $classId = trim($request->class_id);
    $subjectId = trim($request->subject_id);
    $homeworkDate = trim($request->homework_date);
    $submissionDate = trim($request->submission_date);
    $description = strip_tags(trim($request->description));
    $documentFile = null;

    // Handle the document file upload
    if ($request->hasFile('document_file')) {
        $file = $request->file('document_file');
        $filename = date('Ymdhis') . Str::random(20) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/homework'), $filename);
        $documentFile = $filename;
    }

    // Get the authenticated user's ID
    $userId = Auth::user()->id;

    // Call the stored procedure
    DB::statement("CALL insertTeacher(?, ?, ?, ?, ?, ?, ?)", [
        $classId,
        $subjectId,
        $homeworkDate,
        $submissionDate,
        $description,
        $documentFile,
        $userId
    ]);

    return redirect('teacher/homework/homework')->with('success', "Homework successfully created");
}


    public function editTeacher($id)
    {
        $getRecord = HomeworkModel::getSingle($id);
        $data['getRecord'] = $getRecord;
        $data['getSubject'] = ClassSubject::MySubject($getRecord->class_id);
         // Get the logged-in student's email
         $email = Auth::user()->email;

         // Fetch the class_id from the student table using the email
         $teacher = \DB::table('teacher')->where('email', $email)->first();
         if ($teacher) {
            $class_id = $teacher->id;

            $data['getClass'] = AssignClassTeacherModel::getMyClassSubjectGroup($class_id);
            $data['header_title'] = 'Edit Homework';
            return view('teacher.homework.edit', $data); 
        }
        else {
            return redirect()->back()->with('error', 'No student record found for this email.');
        }
        
    }

    // public function updateTeacher(Request $request, $id)
    // {
    //     $homework = HomeworkModel::getSingle($id);
    //     $homework->class_id = trim($request->class_id);
    //     $homework->subject_id = trim($request->subject_id);
    //     $homework->homework_date = trim($request->homework_date);
    //     $homework->submission_date = trim($request->submission_date);
    //     $homework->description = trim($request->description);
        

    //     if(!empty($request->file('document_file')))
    //     {
    //         $ext = $request->file('document_file')->getClientOriginalExtension();
    //         $file = $request->file('document_file');
    //         $randomStr = date('Ymdhis').Str::random(20);
    //         $filename = strtolower($randomStr).'.'.$ext;
    //         $file->move('uploads/homework/', $filename);

    //         $homework->document_file = $filename;

    //     }

    //     $homework->save();

    //     return redirect('teacher/homework/homework')->with('success',"Homework successfully updated");
    // } 
   

public function updateTeacher(Request $request, $homeworkId)
{
    // Fetch data from the request
    $classId = $request->input('class_id');
    $subjectId = $request->input('subject_id');
    $homeworkDate = $request->input('homework_date');
    $submissionDate = $request->input('submission_date');
    $description = $request->input('description');
    $documentFile = null;
    
    // Check if a new file is uploaded
    if ($request->hasFile('document_file')) {
        $file = $request->file('document_file');
        $documentFile = date('Ymdhis') . Str::random(20) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/homework'), $documentFile);
    }
    
    $userId = Auth::user()->id;

    // Call the stored procedure
    DB::statement("CALL updateTeacher(?, ?, ?, ?, ?, ?, ?, ?)", [
        $homeworkId,
        $classId,
        $subjectId,
        $homeworkDate,
        $submissionDate,
        $description,
        $documentFile,
        $userId
    ]);

    return redirect('teacher/homework/homework')->with('success', "Homework successfully updated");
}





    //student side work 
    // public function HomeworkStudent()
    // {
    //     $email = Auth::user()->email;

    //     // Fetch the teacher record using the email
    //     $student = DB::table('student')->where('email', $email)->first();

    //     if (!$student) {
    //         return redirect()->back()->with('error', 'student not found');
    //     }
    //     $data['getRecord'] = HomeworkModel::getRecord($student->id);
    //     $data['header_title'] = 'My Homework';
    //     return view('student.homework.list', $data);

        
    //  }

    public function HomeworkStudent()
{
    $email = Auth::user()->email;

    // Fetch the student record using the email
    $student = DB::table('student')->where('email', $email)->first();

    if (!$student) {
        return redirect()->back()->with('error', 'Student not found');
    }

    // Fetch the homework records specific to the student's class
    $data['getRecord'] = HomeworkModel::getRecord($student->id);
    $data['header_title'] = 'My Homework';
    return view('student.homework.list', $data);
}


    public function SubmitHomework($homework_id)
    {
        $email = Auth::user()->email;

        // Fetch the student record using the email
        $student = DB::table('student')->where('email', $email)->first();
    
        if (!$student) {
            return redirect()->back()->with('error', 'Student not found');
        }
    
        // Fetch the homework records specific to the student's class
        $data['getRecord'] = HomeworkModel::getSingle($homework_id);
        $data['header_title'] = 'Submit My Homework';
        return view('student.homework.submit', $data); 
    }


//     public function SubmitHomework($homework_id)
// {
//     $email = Auth::user()->email;

//     // Fetch the student record using the email
//     $student = DB::table('student')->where('email', $email)->first();

//     if (!$student) {
//         return redirect()->back()->with('error', 'Student not found');
//     }

//     // Get the student id
//     $student_id = $student->id;

//     // Get the homework description and document file (these values can come from the request)
//     $description = 'Sample Description';  // Replace with actual input
//     $document_file = 'sample.pdf';  // Replace with actual file name if applicable

//     // Call the stored procedure
//     DB::select('CALL submit_homework_procedure(?, ?, ?, ?)', [
//         $homework_id,
//         $student_id,
//         $description,
//         $document_file
//     ]);

//     // Redirect or return a response
//     return redirect()->route('homework.submissions')->with('success', 'Homework submitted successfully');
// }


    

    // public function SubmitHomework($homework_id)
    // {
    //     $data['getRecord'] = HomeworkMode::getSingle($homework_id);
    //     $data['header_title'] = 'Submit My Homework';
    //     return view('student.homework.list',$data);
    // }







public function SubmitHomeworkInsert($homework_id, Request $request) 
{
    $homework = new HomeworkSubmitModel;
    $homework->homework_id = $homework_id;
    $email = Auth::user()->email;

    // Fetch the student record using the email
    $student = \DB::table('student')->where('email', $email)->first();
    if ($student) {
        $class_id = $student->id;

        // Set student_id in the HomeworkSubmitModel
        $homework->student_id = $student->id;

        // Set homework details
        $homework->description = trim($request->description);

        if (!empty($request->file('document_file'))) {
            $ext = $request->file('document_file')->getClientOriginalExtension();
            $file = $request->file('document_file');
            $randomStr = date('Ymdhis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->move('uploads/homework/', $filename);

            $homework->document_file = $filename;
        }

        // Save homework and redirect
        $homework->save();
        return redirect('student/my_homework')->with('success', "Homework successfully submitted");
    } else {
        // If no student found, return with an error
        return redirect()->back()->with('error', 'No student record found for this email.');
    }
}

public function HomewarkSubmitedStudent(Request $request)
{
    $email = Auth::user()->email;

    // Fetch the student record using the email
    $student = DB::table('student')->where('email', $email)->first();

    if (!$student) {
        return redirect()->back()->with('error', 'Student not found');
    }

    // Fetch the homework records specific to the student's class
    $data['getRecord'] = HomeworkSubmitModel::getRecordStudent($student->id); // Change this line
    $data['header_title'] = 'My Submitted Homework';
    return view('student.homework.submitted_list', $data); 
}


}
