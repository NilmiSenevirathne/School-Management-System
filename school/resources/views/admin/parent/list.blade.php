@extends('layouts.app')

@section('content')
 

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Parent List(Total :{{ $getRecord-> total() }})</h1>
          </div>
          <div class="col-sm-6" style ="text-align:right;">
            <a href="{{ url('admin/parent/add') }}" class="btn btn-primary">Add new Parent</a>

          </div>
          
        </div>
      </div>
    </section>

    <section class ="content">
    <div class="row">
     
      <div class="col-md-12">
       
        <div class="card ">
          <div class="card-header">
            <h3 class="card-title">Search Parent</h3>
          </div>
        
          <form method="get" action ="">
          
            <div class="card-body">
              <div class ="row">
                <div class="form-group col-md-3">
                    <label >First Name</label>
                    <input type="text" class="form-control" value ="{{ Request::get('name') }}" name ="name" placeholder="Enter name">
                  </div>
                  <div class="form-group col-md-3">
                    <label >Last Name</label>
                    <input type="text" class="form-control" value ="{{ Request::get('last_name') }}" name ="last_name" placeholder="Enter last name">
                  </div>
              <div class="form-group col-md-3">
                <label>Email address</label>
                <input type="text" class="form-control" value ="{{ Request::get('email') }}" name ="email"   placeholder="Enter Email">
              </div>
              <div class="form-group col-md-3">
                <label>Gender</label>
                <select class ="form-control"  name ="gender">
                  <option value ="">Select Gender</option>
                  <option {{ (Request::get('gender') == 'Male') ? 'selected': '' }} value ="Male">Male</option>
                  <option {{ (Request::get('gender') == 'Female') ? 'selected': '' }} value ="Female">Female</option>
              </select>
              </div>

              <div class="form-group col-md-3">
                <button class = "btn btn-primary" type = "submit" style = "margin-top:30px;">Search</button>
                <a href ="{{ url('admin/parent/list') }}" class = "btn btn-success" style ="margin-top:30px;">Reset</a>
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
                <h3 class="card-title">Parent List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Profile</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Address</th>
                      <th>Contact</th>
                      <th>Gender</th>
                      <th>Occupation</th>
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
                        @if(!empty($value->getParentProfile()))
                        <img src = "{{ $value->getParentProfile() }}" style = "height:50px; width:50px; border-radius:50px;">
                        @endif
                        </td>
                    <td>{{ $value->name }} {{ $value->last_name }}</td>
                    <td>{{ $value->email }}</td>
                    <td>{{ $value->address }}</td>
                    <td>{{ $value->contact }}</td>
                    <td>{{ $value->gender }}</td>
                    <td>{{ $value->occupation }}</td>
                    <td>{{ ($value->status == 0) ? 'Active' : 'Inactive' }}</td>
          
                    <td>{{ date('d-m-Y',strtotime($value->created_at))}}</td>
                     
                    <td>
                      <a href ="{{ url('admin/parent/edit/'.$value->id) }}" class ="btn btn-primary">Edit</a>
                      <a href ="{{ url('admin/parent/delete/'.$value->email) }}" class ="btn btn-danger">Delete</a>
                      <a href ="{{ url('admin/parent/my-student/'.$value->id) }}" class ="btn btn-primary">My Student</a>

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