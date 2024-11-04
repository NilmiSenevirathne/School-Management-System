@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Assign Class Teacher (Total: {{ $getRecord->count() }})</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ url('admin/assign_class_teacher/add') }}" class="btn btn-primary">Add New Assign Class Teacher</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Search Assign Class Teacher</h3>
                    </div>

                    <form method="get" action="">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>Class Name</label>
                                    <input type="text" class="form-control" value="{{ Request::get('class_name') }}" name="class_name" placeholder="Enter class name">
                                </div>

                                <div class="form-group col-md-2">
                                    <label>Teacher Name</label>
                                    <input type="text" class="form-control" value="{{ Request::get('teacher_name') }}" name="teacher_name" placeholder="Enter teacher name">
                                </div>

                                <div class="form-group col-md-2">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="1" {{ Request::get('status') == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ Request::get('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-2">
                                    <label>Date</label>
                                    <input type="date" class="form-control" value="{{ Request::get('date') }}" name="date">
                                </div>

                                <div class="form-group col-md-3">
                                    <button class="btn btn-primary" type="submit" style="margin-top:30px;">Search</button>
                                    <a href="{{ url('admin/assign_class_teacher/list') }}" class="btn btn-success" style="margin-top:30px;">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @include('_message')

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Assign Class Teacher List</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Class Name</th>
                                <th>Teacher Name</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                        @if($getRecord->isNotEmpty())
                            @foreach ($getRecord as $value)
                                <tr>
                                    <td>{{ $value->id }}</td>
                                    <td>{{ $value->class_name ?? 'N/A' }}
                                    <td>{{ $value->teacher_name ?? 'N/A' }}</td>
                                    <td>{{ $value->status == 0 ? 'Active' : 'Inactive' }}</td>
                                    <td>{{ $value->created_by_name ?? 'N/A' }}</td> 
                                    <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                    <td>
                                        <a href="{{ route('admin.assign_class_teacher.edit', $value->id) }}" class="btn btn-primary">Edit</a>
                                        <a href="{{ route('admin.assign_class_teacher.edit_single', $value->id) }}" class="btn btn-warning">Edit Single</a>
                                        <form action="{{ route('admin.assign_class_teacher.delete', $value->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">No records found</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
