@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>My Student Exam Result</h1>
                    </div>

                </div>
            </div>
        </section>


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">

            @foreach($getRecord as $value)
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $value['exam_name'] }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Home Work</th>
                                    <th>Test Work</th>
                                    <th>Exam Marks</th>
                                    <th>Total Score</th>
                                    <th>Grade</th>
                                    <th>Passing Marks</th>
                                    <th>Full Marks</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grand_total_score = 0;
                                @endphp
                                @foreach ($value['subject'] as $exam)
                                  
                                    <tr>
                                        <td>{{ $exam['subject_name'] }}</td>
                                        <td>{{ $exam['home_work'] }}</td>
                                        <td>{{ $exam['test_work'] }}</td>
                                        <td>{{ $exam['exam'] }}</td>
                                        <td>{{ $exam['total_marks'] }}</td>  <!-- Directly displaying total_score -->
                                        <td>{{ $exam['grade'] }}</td>
                                        <td>{{ $exam['passing_marks'] }}</td>
                                        <td>{{ $exam['full_marks'] }}</td>
                                        <td>
                                            @if($exam['total_marks'] >= $exam['passing_marks'])
                                                <span style="color:green; font-weight:bold;">Pass</span>
                                            @else
                                                <span style="color:red; font-weight:bold;">Fail</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            @endforeach
        </div>
    </div>
            <!-- /.col -->
    </div>

    </section>
@endsection
