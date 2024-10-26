@extends('layouts.app')

@section('content')
 

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Student Attendance</h1>
          </div>
          <div class="col-sm-6" style ="text-align:right;">
            <a href="{{ url('admin/attendance/student') }}" class="btn btn-primary">Add Student Attendance</a>

          </div>
          
        </div>
      </div>
    </section>

    <section class ="content">
    <div class="row">
     
      <div class="col-md-12">
       
        <div class="card ">
          <div class="card-header">
            <h3 class="card-title">Search Student Attendance</h3>
          </div>
        
          <form method="get" action ="">
          
            <div class="card-body">
              <div class ="row">
                <div class="form-group col-md-3">
                    <label >Student Name</label>
                    <input type="text" class="form-control" value ="{{ Request::get('class_id') }}" name ="class_id" placeholder="Enter name" required>
                  </div>
            
              <div class="form-group col-md-3">
                <label>Date</label>
                <input type="date" class="form-control" value ="{{ Request::get('attendance_date') }}" required name ="attendance_date"   placeholder="Enter Email">
              </div>

              <div class="form-group col-md-3">
                <button class = "btn btn-primary" type = "submit" style = "margin-top:30px;">Search</button>
                <a href ="{{ url('admin/attendance/student') }}" class = "btn btn-success" style ="margin-top:30px;">Reset</a>
              </div>
            </div>
            </div>
          </form>
        </div>

        @if(!empty(Request::get('class_id')) && !empty(Request::get('attendance_date')))
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Student List</h3>
                </div>
                <div clss="card-body p-0" style="overflow: auto;">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Attendance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($getStudent) && !empty($getStudent->count()))
                                @foreach($getStudent as $value)
                                <tr>
                                    <td>{{ $value->id }}</td>
                                    <td>{{ $value->name }} {{ $value->last_name }}</td>
                                    <td>
                                        <label style="margin-right: 10px;"><input type="radio" name="attendance{{ $value->id }}">Present</label>
                                        <label style="margin-right: 10px;"><input type="radio" name="attendance{{ $value->id }}">Late</label>
                                        <label style="margin-right: 10px;"><input type="radio" name="attendance{{ $value->id }}">Absent</label>
                                        <label style="margin-right: 10px;"><input type="radio" name="attendance{{ $value->id }}">Half Day</label>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
      </div>
    </div>
  </section>

   
  

@endsection