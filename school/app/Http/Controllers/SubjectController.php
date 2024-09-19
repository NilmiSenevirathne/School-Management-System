<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    
    public function list()
    {
        $data['getRecord'] = Subject::getRecord();

        $data['header_title'] = 'Subject List';
        return view ('admin.subject.list',$data);
    }

    public function  add()
    {
        $data['header_title'] = 'Add Subject';
        return view ('admin.subject.add',$data);
    }

    public static function insert(Request $request)
    {
        $save = new  Subject();
        $save->name = trim($request->name);
        $save->type =  trim($request->type);
        $save->status = trim($request->status);
        $save->created_by = Auth::user()->id;
        $save->save();

        return redirect('admin/subject/list')->with('success','Subject added successfully');

    }

    public function edit($id)
    {
        $data['getRecord'] = Subject::getSingle($id);
        if(!empty($data['getRecord']))
        {

            $data['header_title'] = 'Edit Subject';
            return view ('admin.subject.edit',$data);
        }
        else{
            abort(404);
        }
    }

    public function update(Request $request ,$id)
    {

        $save =  Subject::getSingle($id);
        $save->name = trim($request->name);
        $save->type =  trim($request->type);
        $save->status = trim($request->status);
        $save->save();

        return redirect('admin/subject/list')->with('success','Subject updated successfully');
    }

    public function delete($id)
    {
        
        $save =  Subject::getSingle($id);
        $save->is_delete = 1;
        $save->save();

        return redirect()->back()->with('success','Subject deleted successfully');

    }
}
