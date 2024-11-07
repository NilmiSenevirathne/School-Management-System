@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>My Student List</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class ="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card ">
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
                        <h3 class="card-title">Parent Student List</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Profile</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>parent Name</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($getRecord as $value)
                                    <tr>
                                        <td>{{ $value->id }}</td>
                                        <td>
                                            @if (!empty($value->getStudentProfile()))
                                                <img src = "{{ $value->getStudentProfile() }}"
                                                    style = "height:50px; width:50px; border-radius:50px;">
                                            @endif
                                        </td>
                                        <td>{{ $value->name }} {{ $value->last_name }}</td>
                                        <td>{{ $value->email }}</td>
                                        <td>{{ $value->parent_name }} {{ $value->parent_last_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                                        <td>
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ url('parent/my_student/subject/' . $value->id) }}">Subject</a>
                                       
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ url('parent/my_student/exam_result/' . $value->id) }}">Exam Result</a>
                                        </td>
                                        {{-- 
                        <td style ="min-width:150px;">
                           <a href ="{{ url('admin/parent/assign_student_parent_delete/' . $value->id) }}"
                              class ="btn btn-danger btn-sm">Delete</a>
                        </td>
                        --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div style ="padding:10px; float:right;">
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col -->
    </div>
    </section>
@endsection
