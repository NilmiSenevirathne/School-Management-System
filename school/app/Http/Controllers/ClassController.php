<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function list()
    {
        $data['getRecord'] = ClassModel::getRecord();

        $data['header_title'] = 'Class List';

        return view('admin.class.list', $data);
    }

    public function add()
    {
        $data['header_title'] = 'Add New Class';

        return view('admin.class.add', $data);
    }

    // public function insert(Request $request)
    // {
    //     $save = new ClassModel;
    //     $save->name = $request->name;
    //     $save->status = $request->status;
    //     $save->created_by = Auth::user()->id;
    //     $save->save();

    //     return redirect ('admin/class/list')->with('success','Class added successfully');

    // }

    public function insert(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|integer',
        ]);

        // Call the stored procedure
        DB::statement('CALL add_class(?, ?, ?)', [
            $request->name,
            $request->status,
            Auth::user()->id,
        ]);

        return redirect('admin/class/list')->with('success', 'Class added successfully');
    }

    public function edit($id)
    {
        $data['getRecord'] = ClassModel::getSingle($id);
        if (! empty($data['getRecord'])) {

            $data['header_title'] = 'Edit Class';

            return view('admin.class.edit', $data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request, $id)
    {
        DB::statement('CALL UpdateClass(?, ?, ?)', [
            $id,
            $request->name,
            $request->status,
        ]);

        return redirect('admin/class/list')->with('success', 'Class updated successfully');
    }

    public function delete($id)
    {
        DB::statement('CALL DeleteClass(?)', [$id]);

        return redirect()->back()->with('success', 'Class deleted successfully');
    }
}
