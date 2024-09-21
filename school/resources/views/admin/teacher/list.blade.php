@extends('layouts.app')

@section('content')
 

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Teacher List(Total :{{ $getRecord-> total() }})</h1>
          </div>
          <div class="col-sm-6" style ="text-align:right;">
            <a href="{{ url('admin/teacher/add') }}" class="btn btn-primary">Add new Teacher</a>

          </div>
          
        </div>
      </div>
    </section>

    <section class ="content">
    <div class="row">
     
      <div class="col-md-12">
       
        <div class="card ">
          <div class="card-header">
            <h3 class="card-title">Search Student</h3>
          </div>
        
          <form method="get" action ="">
          
            <div class="card-body">
              <div class ="row">
                <div class="form-group col-md-2">
                    <label >First Name</label>
                    <input type="text" class="form-control" value ="{{ Request::get('name') }}" name ="name" placeholder="Enter name">
                  </div>
                  <div class="form-group col-md-2">
                    <label >Last Name</label>
                    <input type="text" class="form-control" value ="{{ Request::get('last_name') }}" name ="last_name" placeholder="Enter last name">
                  </div>
              <div class="form-group col-md-2">
                <label>Email address</label>
                <input type="text" class="form-control" value ="{{ Request::get('email') }}" name ="email"   placeholder="Enter Email">
              </div>
             
              <div class="form-group col-md-2">
                <label>Gender</label>
                <select class ="form-control"  name ="gender">
                  <option value ="">Select Gender</option>
                  <option {{ (Request::get('gender') == 'Male') ? 'selected': '' }} value ="Male">Male</option>
                  <option {{ (Request::get('gender') == 'Female') ? 'selected': '' }} value ="Female">Female</option>
              </select>
              </div>
              <div class="form-group col-md-2">
                <label>Status</label>
                <select class="form-control" name="status">
                  <option value="">Select Status</option>
                  <option {{ (Request::get('status') == '0') ? 'selected' : '' }} value="0">Active</option>
                  <option {{ (Request::get('status') == '1') ? 'selected' : '' }} value="1">Inactive</option>
              </select>
              
              </div>
              

              <div class="form-group col-md-2">
                <button class = "btn btn-primary" type = "submit" style = "margin-top:30px;">Search</button>
                <a href ="{{ url('admin/teacher/list') }}" class = "btn btn-success" style ="margin-top:30px;">Reset</a>
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
                <h3 class="card-title">Student List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0" style ="overflow:auto;">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Profile</th>
                      <th>Teacher Name</th>
                      <th>Email</th>
                      <th>Address</th>                     
                      <th>Gender</th>
                      <th>Date of Birth</th>
                      <th>Date of Join</th>
                      <th>Contact</th>
                      <th>Qualification</th>
                      <th>Experience</th>
                      <th>Note</th>
                      <th>Status</th>
                      <th>Created Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($getRecord as $value)
                  <tr>
                    <td>{{ $value->id }}</td>
                    <td>
                      @if(!empty($value->getTeacherProfile()))
                      <img src = "{{ $value->getTeacherProfile() }}" style = "height:50px; width:50px; border-radius:50px;">
                      @endif
                      </td>
                    <td>{{ $value->name }} {{ $value->last_name }}</td>
                    <td>{{ $value->email }}</td>
                    <td>{{ $value->address }}</td>
                    <td>{{ $value->gender }}</td>
                    <td>{{ $value->date_of_birth }}</td>
                    <td>{{ $value->date_of_join }}</td>
                    <td>{{ $value->contact }}</td>
                    <td>{{ $value->qualification }}</td>
                    <td>{{ $value->experience }}</td>
                    <td>{{ $value->note }}</td>
                    <td>{{ ($value->status == 0) ? 'Active' : 'Inactive' }}</td>
                    
                    <td>{{ date('d-m-Y',strtotime($value->created_at))}}</td>
                     
                    <td style ="min-width:150px;">
                      <a href ="{{ url('admin/teacher/edit/'.$value->id) }}" class ="btn btn-primary btn-sm">Edit</a>
                      <a href ="{{ url('admin/teacher/delete/'.$value->email) }}" class ="btn btn-danger btn-sm">Delete</a>
                    </td>
                  </tr>
                  @endforeach
                  </tbody>
                </table>
                <div style ="padding:10px; float:right;">
                {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
              </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
     
    </section>
  

@endsection