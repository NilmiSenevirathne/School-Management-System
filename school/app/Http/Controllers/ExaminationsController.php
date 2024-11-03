<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\ClassSubject;
use App\Models\ExamSchedule;
use Illuminate\Http\Request;
use App\Models\MarksRegister;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExaminationsController extends Controller
{
     // Method to display the exam list
    public function exam_list()
    {
        // Call the stored procedure
        $data['getRecord'] = DB::select('CALL GetExamRecords()');
        $data['header_title'] = "Exam List";
        
        return view('admin.examinations.exam.list', $data);
    }

    public function exam_add()
    {

        $data['header_title'] ="Add New Exam";
        return view('admin.examinations.exam.add',$data);
    }

    public function exam_insert(Request $request)
    {
      

        $exam = new Exam;
        $exam->name = trim($request->name);
        $exam->note = trim($request->note);
        $exam->created_by = Auth::user()->id;
        $exam->save();

        return redirect('admin/examinations/exam/list')->with('success', 'Exam added successfully');

    }

    public function exam_edit($id)
   {
    $data['getRecord'] = Exam::getSingle($id);
    if(!empty($data['getRecord']))
    {

        $data['header_title'] ="Edit Exam";
        return view('admin.examinations.exam.edit',$data);
    }
    else{
        abort(404);
    }
   
   }

   public function exam_update(Request $request,$id)
   {
     

       $exam = Exam::getSingle($id);
       $exam->name = trim($request->name);
       $exam->note = trim($request->note);
       $exam->save();

       return redirect('admin/examinations/exam/list')->with('success', 'Exam updated successfully');

   }

   public function exam_delete($id)
   {
    $getRecord = Exam::getSingle($id);
    if(!empty($getRecord))
    {
       $getRecord->is_delete = 1;
       $getRecord->save();

       return redirect()->back()->with('success', 'Exam deleted successfully');

    }
    else{
        abort(404);
    }
   
   }

   public function exam_schedule(Request $request)
   {
    $data['getClass'] = ClassModel::getClass();
    $data['getExam'] = Exam::getExam();


    $result = array();

    if(!empty($request->get('exam_id')) && !empty($request->get('class_id')))
    {
        $getSubject = ClassSubject::MySubject($request->get('class_id'));

        foreach($getSubject as $value)
        {
            $dataS = array();
            $dataS['subject_id'] = $value->subject_id;
            $dataS['class_id'] = $value->class_id;
            $dataS['subject_name'] = $value->subject_name;
            $dataS['subject_type'] = $value->subject_type;

            $ExamSchedule = ExamSchedule::getRecordSingle($request->get('exam_id'),$request->get('class_id'),$value->subject_id);

            if(!empty($ExamSchedule))
            {

                $dataS['exam_date'] = $ExamSchedule->exam_date;
                $dataS['start_time'] = $ExamSchedule->start_time;
                $dataS['end_time'] = $ExamSchedule->end_time;
                $dataS['location'] = $ExamSchedule->location;
                $dataS['full_marks'] = $ExamSchedule->full_marks;
                $dataS['passing_marks'] = $ExamSchedule->passing_marks;
            }
            else{

                $dataS['exam_date'] = '';
                $dataS['start_time'] = '';
                $dataS['end_time'] = '';
                $dataS['location'] = '';
                $dataS['full_marks'] = '';
                $dataS['passing_marks'] = '';
            }



            $result[] = $dataS;
        }
    }
    $data['getRecord'] = $result;

    $data['header_title'] ="Exam Schedule";
    return view('admin.examinations.exam_schedule',$data);
   }

public function exam_schedule_insert(Request $request)
{
    ExamSchedule::deleteRecord($request->exam_id,$request->class_id);
    if (!empty($request->schedule)) {
        foreach ($request->schedule as $schedule) {
            if (!empty($schedule['subject_id']) && !empty($schedule['exam_date']) && !empty($schedule['start_time']) && !empty($schedule['end_time']) && !empty($schedule['location'])
                && !empty($schedule['full_marks']) && !empty($schedule['passing_marks'])) {
                
                $exam = new ExamSchedule;

                $exam->exam_id = $request->exam_id;
                $exam->class_id = $request->class_id;
                $exam->subject_id = $schedule['subject_id']; // Corrected array access
                $exam->exam_date = $schedule['exam_date']; // Corrected array access
                $exam->start_time = $schedule['start_time']; // Corrected array access
                $exam->end_time = $schedule['end_time']; // Corrected array access
                $exam->location = $schedule['location']; // Corrected array access
                $exam->full_marks = $schedule['full_marks']; // Corrected array access
                $exam->passing_marks = $schedule['passing_marks']; // Corrected array access
                $exam->created_by = Auth::user()->id;
                $exam->save();
            }
        }
    }

    return redirect()->back()->with('success', 'Exam scheduled successfully');
}

public function marks_register(Request $request)
{
    $data['getClass'] = ClassModel::getClass();
    $data['getExam'] = Exam::getExam();

    if(!empty($request->get('exam_id')) && !empty($request->get('class_id')))
    {
        $data['getSubject'] = ExamSchedule::getSubject($request->get('exam_id'),$request->get('class_id'));
        $data['getStudent'] = Student::getStudentClass($request->get('class_id'),$request->get('class_id'));

        
    }

    $data['header_title'] ="Marks Register";
    return view('admin.examinations.marks_register',$data);
}

public function submit_marks_register(Request $request)
{
    
        $validation = 0;
        if (!empty($request->mark)) {
            foreach ($request->mark as $mark) {

                $getExamSchedule = ExamSchedule::getSingle($mark['id']);
                $full_marks = $getExamSchedule->full_marks;


                $home_work = !empty($mark['home_work']) ? $mark['home_work'] : 0;
                $test_work = !empty($mark['test_work']) ? $mark['test_work'] : 0;
                $exam = !empty($mark['exam']) ? $mark['exam'] : 0;

                $total_mark = $home_work + $test_work + $exam;

                if($full_marks >= $total_mark)
                {

                $getMark = MarksRegister::CheckAlreadyMark($request->student_id,$request->exam_id,$request->class_id,$mark['subject_id']);

                if(!empty($getMark))
                {
                    $save = $getMark;
                }
                else
                {
                    $save = new MarksRegister;
                    $save->created_by = Auth::user()->id;


                }
                $save->student_id = $request->student_id;
                $save->exam_id = $request->exam_id;
                $save->class_id = $request->class_id;
                $save->subject_id = $mark['subject_id'];
                $save->home_work = $home_work;
                $save->test_work = $test_work;
                $save->exam = $exam;
                
                $save->save();
            }
            else{
                $validation = 1;
            }
            }
        }
        if($validation == 0)
        {
            $json['message'] = "Marks Register Successfully!";
        }
        else
        {
            $json['message'] = "Marks Register Successfully!..Some Subject Marks Greater than Full Marks";
        }
        echo json_encode($json);
}


public function single_submit_marks_register(Request $request)
{

    $id = $request->id;
    $getExamSchedule = ExamSchedule::getSingle($id);

    $full_marks = $getExamSchedule->full_marks;

    $home_work = !empty($request->home_work) ? $request->home_work: 0;
    $test_work = !empty($request->test_work) ? $request->test_work: 0;
    $exam = !empty($request->exam) ? $request->exam: 0;

    $total_mark = $home_work + $test_work + $exam;

    if($full_marks >= $total_mark)
    {
        $getMark = MarksRegister::CheckAlreadyMark($request->student_id,$request->exam_id,$request->class_id,$request->subject_id);

        if(!empty($getMark))
        {
            $save = $getMark;
        }
        else
        {
            $save = new MarksRegister;
            $save->created_by = Auth::user()->id;
    
    
        }
        $save->student_id = $request->student_id;
        $save->exam_id = $request->exam_id;
        $save->class_id = $request->class_id;
        $save->subject_id = $request->subject_id;
        $save->home_work = $home_work;
        $save->test_work = $test_work;
        $save->exam = $exam;
        
        $save->save();
    
        $json['message'] = "Mark Register saved successfully";
        
    }    
        else
        {
            $json['message'] = "Total marks should not be greater than full marks";
        }
        echo json_encode($json);

}

// student side


public function MyExamTimetable(Request $request)
{
    $email = Auth::user()->email;
    $student = \DB::table('student')->where('email', $email)->first();

    if ($student) {
        $class_id = $student->class_id;
        $getExam = ExamSchedule::getExam($class_id);
        $result = [];

        foreach ($getExam as $value) {
            $dataE = [];
            $dataE['exam_name'] = $value->exam_name; // Add exam_name here
            $dataE['exam'] = []; // Initialize exam array
            $getExamTimetable = ExamSchedule::getExamTimetable($value->exam_id, $class_id);

            foreach($getExamTimetable as $valueS) {
                $dataS = [];
                $dataS['subject_name'] = $valueS->subject_name;
                $dataS['exam_date'] = $valueS->exam_date;
                $dataS['start_time'] = $valueS->start_time;
                $dataS['end_time'] = $valueS->end_time;
                $dataS['location'] = $valueS->location;
                $dataS['full_marks'] = $valueS->full_marks;
                $dataS['passing_marks'] = $valueS->passing_marks;

                $dataE['exam'][] = $dataS; // Append subjects to the exam array
            }

            $result[] = $dataE; // Append the whole exam data to result
        }

        $data['getRecord'] = $result;
        $data['header_title'] = "My Exam Timetable";
        return view('student.MyExamTimetable', $data);
    }
}


}
