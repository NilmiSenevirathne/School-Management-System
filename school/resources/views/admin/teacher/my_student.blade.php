@extends('layouts.app')

@section('content')
 

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>My Student List </h1>
          </div>
         
          
        </div>
      </div>
    </section>

    <section class ="content">
    <div class="row">
     
      <div class="col-md-12">
    

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
                      <th>Profile</th>
                      <th>Student Name</th>
                      <th>Email</th>
                      <th>Address</th>
                      <th>Class</th>
                      <th>Gender</th>
                      <th>Date of Birth</th>
                      <th>Roll Number</th>
                      <th>Admission Number</th>
                      <th>Admission Date</th>
                      <th>Contact</th>
                      <th>Created Date</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($getRecord as $value)
                  <tr>
                    <td>
                      @if(!empty($value->getStudentProfile()))
                      <img src = "{{ $value->getStudentProfile() }}" style = "height:50px; width:50px; border-radius:50px;">
                      @endif
                      </td>
                    <td>{{ $value->name }} {{ $value->last_name }}</td>
                    <td>{{ $value->email }}</td>
                    <td>{{ $value->address }}</td>
                    <td>{{ $value->class_name }}</td>
                    <td>{{ $value->gender }}</td>
                    <td>{{ $value->date_of_birth }}</td>
                    <td>{{ $value->roll_number }}</td>
                    <td>{{ $value->admission_number	}}</td>
                    <td>{{ $value->admission_date	}}</td>
                    <td>{{ $value->contact }}</td>
                    
                    <td>{{ date('d-m-Y',strtotime($value->created_at))}}</td>
                     
                    
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