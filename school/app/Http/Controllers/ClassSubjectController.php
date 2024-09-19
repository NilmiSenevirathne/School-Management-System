<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\ClassSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassSubjectController extends Controller
{
    public function list()
    {
        $data['getRecord'] = ClassSubject::getRecord();

        $data['header_title'] = 'Assign Subject List';
        return view ('admin.assign-subject.list',$data);
    }


    public function add(Request $request)
    {

        $data['getClass'] = ClassModel:: getClass();
        $data['getSubject'] = Subject:: getSubject();
        $data['header_title'] = 'Assign Subject Add';
        return view ('admin.assign-subject.add',$data);
    }

    public function insert(Request $request)
    {

        if(!empty($request->subject_id))
        {
            foreach ($request->subject_id as $subject_id)

            {
                $getAlreadyFirst = ClassSubject::getAlreadyFirst($request->class_id,$subject_id);
                if(!empty($getAlreadyFirst))
                {
                    $getAlreadyFirst->status = $request->status;
                    $getAlreadyFirst>save();

                }
                else
                {
                    $save = new ClassSubject;
                    $save->class_id = $request->class_id;
                    $save->subject_id = $subject_id;
                    $save->status = $request->status;
                    $save->created_by = Auth::user()->id;
                    $save->save();
                }
            }
            return redirect('admin/assign-subject/list')->with('success', 'Subject successfully Assign to class');


        }
        else{
            return redirect()->back()->with('error','Due to some error pls try again !');
        }
    }

    public function edit($id)
    {
        $getRecord = ClassSubject::getSingle($id);  
        if(!empty($getRecord))
        {
            $data['getRecord'] = $getRecord;
            $data['getAssignSubjectID'] = ClassSubject::getAssignSubjectID($getRecord->class_id);
            $data['getClass'] = ClassModel:: getClass();
            $data['getSubject'] = Subject:: getSubject();
            $data['header_title'] = 'Assign Subject Edit';
            return view ('admin.assign-subject.edit',$data);
        }
        else{
            abort(404);
        }
       
    }

    public function update(Request $request)
    {
        ClassSubject:: deleteSubject($request->class_id);

        if(!empty($request->subject_id))
        {
            foreach ($request->subject_id as $subject_id)

            {
                $getAlreadyFirst = ClassSubject::getAlreadyFirst($request->class_id,$subject_id);
                if(!empty($getAlreadyFirst))
                {
                    $getAlreadyFirst->status = $request->status;
                    $getAlreadyFirst->save();

                }
                else
                {
                    $save = new ClassSubject;
                    $save->class_id = $request->class_id;
                    $save->subject_id = $subject_id;
                    $save->status = $request->status;
                    $save->created_by = Auth::user()->id;
                    $save->save();
                }
            }
        }
        return redirect('admin/assign-subject/list')->with('success', 'Subject successfully Updated');

    }

    public function delete($id)
    {
        $save = ClassSubject::getSingle($id);
        $save->is_delete = 1;
        $save->save();

        return redirect()->back()->with('success','Record Deleted successfully!');

    }

    public function edit_single($id)
    {
        $getRecord = ClassSubject::getSingle($id);  
        if(!empty($getRecord))
        {
            $data['getRecord'] = $getRecord;
            $data['getClass'] = ClassModel:: getClass();
            $data['getSubject'] = Subject:: getSubject();
            $data['header_title'] = 'Assign Subject Edit';
            return view ('admin.assign-subject.edit_single',$data);
        }
        else{
            abort(404);
        }
    }

    public function update_single(Request $request,$id)
    {
 
                $getAlreadyFirst = ClassSubject::getAlreadyFirst($request->class_id,$request->subject_id);
                if(!empty($getAlreadyFirst))
                {
                    $getAlreadyFirst->status = $request->status;
                    $getAlreadyFirst->save();

                    return redirect('admin/assign-subject/list')->with('success', 'Status successfully Updated');


                }
                else
                {
                    $save = ClassSubject::getSingle($id);;
                    $save->class_id = $request->class_id;
                    $save->subject_id = $request->subject_id;
                    $save->status = $request->status;
                    $save->save();

                    return redirect('admin/assign-subject/list')->with('success', 'Subject successfully Updated');

                }
        
    }
}
