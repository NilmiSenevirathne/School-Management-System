@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Exam Schedule</h1> <!-- Use count() to get the total -->
                </div>
                
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Search Exam Scedule</h3>
                    </div>

                    <form method="get" action="">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>Exam</label>
                                    <select class = "form-control" name ="exam_id" required>
                                        <option value="">Select</option>
                                        @foreach ($getExam as $exam )
                                        <option {{ (Request::get('exam_id') == $exam->id) ? 'selected' : '' }} value = "{{ $exam->id }}">{{ $exam->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Class</label>
                                    <select class = "form-control"name ="class_id" required>
                                        <option value="">Select</option>
                                        @foreach ($getClass as $class )
                                        <option {{ (Request::get('class_id')== $class->id) ? 'selected':'' }} value = "{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <button class="btn btn-primary" type="submit" style="margin-top:30px;">Search</button>
                                    <a href="{{ url('admin/examinations/exam_schedule') }}" class="btn btn-success" style="margin-top:30px;">Reset</a>
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

            @if(!empty($getRecord))
            
            <form action ="{{ url('admin/examinations/exam_schedule_insert') }}" method ="post">
                {{ csrf_field() }}
                <input type = "hidden" name = "exam_id" value = "{{ Request::get('exam_id') }}">
                <input type = "hidden" name = "class_id" value = "{{ Request::get('class_id') }}">



            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Exam Schedule</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                
                                <th>Subject Name</th>
                                <th>Exam Date</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Location</th>
                                <th>Full Marks</th>
                                <th>Passing Marks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                           @foreach ($getRecord as $value)
                           <tr>
                               <td>{{ $value['subject_name'] }}
                                   <input type="hidden" class="form-control" value="{{ $value['subject_id'] }}" name="schedule[{{ $i }}][subject_id]">
                               </td>
                               <td>
                                   <input type="date" class="form-control" value="{{ $value['exam_date'] }}" name="schedule[{{ $i }}][exam_date]">
                               </td>
                               <td>
                                   <input type="time" class="form-control" value="{{ $value['start_time'] }}" name="schedule[{{ $i }}][start_time]">
                               </td>
                               <td>
                                   <input type="time" class="form-control" value="{{ $value['end_time'] }}" name="schedule[{{ $i }}][end_time]">
                               </td>
                               <td>
                                   <input type="text" class="form-control" value="{{ $value['location'] }}" name="schedule[{{ $i }}][location]">
                               </td>
                               <td>
                                   <input type="text" class="form-control" value="{{ $value['full_marks'] }}" name="schedule[{{ $i }}][full_marks]">
                               </td>
                               <td>
                                   <input type="text" class="form-control" value="{{ $value['passing_marks'] }}" name="schedule[{{ $i }}][passing_marks]">
                               </td>
                           </tr>
                           @php
                               $i++;
                           @endphp
                           @endforeach
                           
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            </form>
            @endif

        </div>
    </section>
</div>

@endsection
