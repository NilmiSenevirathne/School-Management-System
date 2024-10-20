@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Assign Class Teacher (Total: {{ count($getRecord) }})</h1>
          </div>
          <div class="col-sm-6" style="text-align:right;">
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
                                <!-- Class Name -->
                                <div class="form-group col-md-3">
                                    <label>Class Name</label>
                                    <input type="text" class="form-control" value="{{ Request::get('class_name') }}" name="class_name" placeholder="Enter class name">
                                </div>

                                <!-- Teacher Name -->
                                <div class="form-group col-md-2">
                                    <label>Teacher Name</label>
                                    <input type="text" class="form-control" value="{{ Request::get('teacher_name') }}" name="teacher_name" placeholder="Enter teacher name">
                                </div>

                                <!-- Status -->
                                <div class="form-group col-md-2">
                                    <label>Status</label>
                                    <select class="form-control" name="status">
                                        <option value="">Select</option>
                                        <option {{ (Request::get('status') == 100) ? 'selected' : '' }} value="100">Active</option>
                                        <option {{ (Request::get('status') == 1) ? 'selected' : '' }} value="1">Inactive</option>
                                    </select>
                                </div>

                                <!-- Date -->
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

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @include('_message')

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Assign Class Teacher List</h3>
                </div>
                <!-- /.card-header -->
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
                            @if(!empty($getRecord) && count($getRecord) > 0)
                                @foreach ($getRecord as $value)
                                    <tr>
                                        <td>{{ $value->id }}</td>
                                        <!-- Modify these lines based on the actual properties returned -->
                                        <td>{{ $value->class_name ?? 'N/A' }}</td> <!-- Updated line -->
                                        <td>{{ $value->teacher_name ?? 'N/A' }}</td> <!-- Updated line -->
                                        <td>
                                            @if($value->status == 0)
                                                Active
                                            @else
                                                Inactive
                                            @endif
                                        </td>
                                        <td>{{ $value->created_by_name }}</td>
                                        <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                                        <td>
                                            <a href="{{ route('admin.assign_class_teacher.edit', $value->id) }}" class="btn btn-primary">Edit</a>
                                            <a href="{{ route('admin.assign_class_teacher.edit_single', $value->id) }}" class="btn btn-primary">Edit Single</a>
                                            <a href="{{ route('admin.assign_class_teacher.delete', $value->id) }}" class="btn btn-danger">Delete</a>
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
                    <!-- Pagination -->
                    
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
