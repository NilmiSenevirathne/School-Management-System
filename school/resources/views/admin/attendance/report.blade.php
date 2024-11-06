@extends('layouts.app')

@section('content')
 

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Attendance Report<span style="color:blue">({{ $getRecord->total() }})</span></h1>
          </div>
        </div>
      </div>
    </section>

    <section class ="content">
    <div class="row">
     
      <div class="col-md-12">
       
        <div class="card ">
          <div class="card-header">
            <h3 class="card-title">Search Student for Attendance</h3>
          </div>
        
          <form method="get" action ="">
          
            <div class="card-body">
              <div class ="row">

              <div class="form-group col-md-2">
                <label>Student ID</label>
                <input type="text" class="form-control" id="getStudentID" value ="{{ Request::get('student_id') }}"  name ="student_id"   placeholder="Student ID">
              </div>

              <div class="form-group col-md-2">
                <label>Student Name</label>
                <input type="text" class="form-control" id="getStudentName" value ="{{ Request::get('student_name') }}"  name ="student_name"   placeholder="Student Name">
              </div>

                <div class="form-group col-md-2">
                    <label >Class Name</label>
                    <select class="form-control" id="getClass" name = "class_id">
                      <option value="">Select class</option>
                      @foreach($getClass as $class)
                        <option value="{{ $class->id }}" {{ (Request::get('class_id') == $class->id )  ? 'selected' : '' }} >
                          {{ $class->name }}
                        </option>
                      @endforeach
                    </select>
                  </div>
            
              <div class="form-group col-md-2">
                <label>Attendance Date</label>
                <input type="date" class="form-control" id="getAttendanceDate" value ="{{ Request::get('attendance_date') }}"  name ="attendance_date"   placeholder="Enter Email">
              </div>

              <div class="form-group col-md-2">
                <label>Attendance Type</label>
                <select class="form-control" name="attendance_type">
                  <option  value="">Select</option>
                  <option {{ (Request::get('attendance_type') == 1 ) ? 'selected' : '' }} value="1">Present</option>
                  <option {{ (Request::get('attendance_type') == 2 ) ? 'selected' : '' }} value="2">Absent</option>
                </select>
              </div>

              <div class="form-group col-md-2">
                <button class = "btn btn-primary" type = "submit" style = "margin-top:30px;">Search</button>
                <a href ="{{ url('admin/attendance/student') }}" class = "btn btn-success" style ="margin-top:30px;">Reset</a>
              </div>
            </div>
            </div>
          </form>
        </div>

       
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Attendance List</h3>
                </div>
                <div clss="card-body p-0" style="overflow: auto;">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Class Name</th>
                                <th>Attendance Type</th>
                                <th>Attendance Date</th>
                                <th>Created By</th>
                                <th>Created Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($getRecord as $value)
                              <tr>
                                <td>{{ $value->student_id }}</td>
                                <td>{{ $value->student_name }} {{ $value->student_last_name }}</td>
                                <td>{{ $value->class_name }}</td>
                                <td>
                                  @if($value->attendance_type == 1)
                                    Present
                                  @elseif($value->attendance_type == 2)
                                    Absent
                                  @endif
                                </td>
                                <td>{{ date('d-m-Y', strtotime($value->attendance_date)) }}</td>
                                <td>{{ $value->created_name }}</td>
                                <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                              </tr>
                            @empty
                              <tr>
                                <td colspan="100%">Record not found</td>
                              </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div style="padding: 10px; float: right;">
                      {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                    </div>
                </div>
            </div>
      </div>
    </div>
  </section>

   
  

@endsection

@section('script').



@endsection