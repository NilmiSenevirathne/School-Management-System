@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Marks Register</h1> <!-- Use count() to get the total -->
                </div>
                
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Search Marks register</h3>
                    </div>

                    <form method="get" action="">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>Exam</label>
                                    
                                        <select class="form-control" name="exam_id" required>
                                            <option value="">Select</option>
                                            @foreach ($getExam as $exam)
                                                <option {{ (Request::get('exam_id') == $exam->id) ? 'selected' : '' }} value="{{ $exam->id }}">{{ $exam->name }}</option>
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
         
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @include('_message')

            @if(!empty($getSubject) && !empty($getSubject->count()))
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Marks Register</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0" style="overflow:auto;">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                
                                <th>Student Name</th>
                                @foreach($getSubject as $subject)
                                <th>
                                    {{ $subject->subject_name }}<br/>
                                    ({{ $subject->subject_type }} : {{ $subject->passing_marks }} / {{ $subject->full_marks }})
                                </th>
                                @endforeach
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(!empty($getStudent) && !empty($getStudent->count()))
                        @foreach ($getStudent as $student )
                        <form method="POST" class="SubmitForm" action =""enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type = "hidden" name = "student_id" value = "{{ $student->id }}">
                            <input type = "hidden" name = "exam_id" value = "{{ Request::get('exam_id')}}">
                            <input type = "hidden" name = "class_id" value = "{{ Request::get('class_id')}}">

                            <tr>
                            <td>{{ $student->name }} {{ $student->last_name }}</td>
                            @php
                                $i = 1;
                                $totalStudentMark = 0;
                                $totalFullMarks = 0;
                                $totalPassingMarks = 0;
                            @endphp
                         @foreach($getSubject as $subject)
                                @php
                                $totalMark = 0;
                                $totalFullMarks = $totalFullMarks + $subject->full_marks;
                                $totalPassingMarks = $totalPassingMarks + $subject->passing_marks;

                                $getMark = $subject->getMark($student->id,Request::get('exam_id'),Request::get('class_id'),$subject->subject_id);

                                if(!empty($getMark))
                                {
                                    $totalMark = $getMark->home_work + $getMark->test_work + $getMark->exam;
                                }
                                $totalStudentMark = $totalStudentMark+$totalMark;
                            @endphp
                            <td>
                                <div style ="margin-bottom:10px;">
                                    Home Work
                                    <input type="hidden" name="mark[{{ $i }}][id]" value="{{ $subject->id }}">
                                    <input type="hidden" name="mark[{{ $i }}][subject_id]" value="{{ $subject->subject_id }}">
                                    <input type="text" name = "mark[{{ $i }}][home_work]" id="home_work_{{ $student->id }}_{{ $subject->subject_id }}" style ="width:200px;" placeholder="Enter marks" value="{{ !empty($getMark->home_work) ? $getMark->home_work:'' }}" class = "form-control" >
                                </div>
                                <div style ="margin-bottom:10px;">
                                    Test Work
                                    <input type="text" name="mark[{{ $i }}][test_work]" id="test_work_{{ $student->id }}_{{ $subject->subject_id }}" style="width:200px;" placeholder="Enter marks" value="{{ !empty($getMark->test_work) ? $getMark->test_work:'' }}"  class="form-control">
                                </div>
                                <div style ="margin-bottom:10px;">
                                    Exam 
                                    <input type="text" name = "mark[{{ $i }}][exam]" id="exam_{{ $student->id }}_{{ $subject->subject_id }}" style ="width:200px;" placeholder="Enter marks" value="{{ !empty($getMark->exam) ? $getMark->exam:'' }}"  class = "form-control" >
                                </div>
                                <div style ="margin-bottom:10px;">
                                    <button type="button" class = "btn btn-primary SaveSingleSubject" id ="{{ $student->id }}" data-val ="{{ $subject->subject_id }}"  data-exam ="{{ Request::get('exam_id') }}" data-schedule ="{{ $subject->id }}" data-class = "{{ Request::get('class_id') }}" >Save</button>
                                </div>

                                @if(!empty($getMark))
                                <div style ="margin-bottom:10px;">
                                  <b> Total Mark : </b> {{ $totalMark }} </br>
                                  <b> Passing Mark : </b> {{ $subject->passing_marks }} </br>
                                  @if($totalMark >= $subject->passing_marks)
                                   <span style ="color:green; font-weight:bold;">Pass</span>
                                   @else
                                   <span style ="color:red; font-weight:bold;">Fail</span>
                                 @endif
                                </div>
                                @endif
                                <br>
                            </td>
                            @php
                                $i++;
                            @endphp
                            @endforeach
                            <td >
                               <button type = "submit" class = "btn btn-success"> Save</button> 
                            </br>
                          <b>Total Student Marks :</b> {{  $totalStudentMark }}
                            </br>
                          <b>Total Subject Marks:</b> {{  $totalFullMarks }}
                            </br>
                          <b>Total Passing Marks :</b>{{  $totalPassingMarks }}
                            </td>
                        </tr>
                        </form>
                        @endforeach
                        @endif
                        </tbody>
                    </table>
                  
                </div>
                <!-- /.card-body -->
            </div>
            @endif
            <!-- /.card -->
        </div>
    </div>
        </div>
    </section>
</div>

@endsection

@section('script')
    <script type = "text/javascript">
       $('.SubmitForm').submit(function(e){
    e.preventDefault(); 
    console.log($(this).serialize()); // Check serialized data
    $.ajax({
        type: "POST",
        url: "{{ url('admin/examinations/submit_marks_register') }}",
        data: $(this).serialize(),
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(data) {
            alert(data.message); // See if successful message returns
        },
        error: function(xhr, status, error) {
            console.error("Error: " + error);  
            console.log(xhr.responseText);
        }
    });
});


// Save single subject mark when user clicks on Save button
    // $('.SaveSingleSubject').click(function(e){
    //     var student_id = $(this).attr('id');
    //     var subject_id = $(this).data('data-val');
    //     var exam_id = $(this).data('data-exam');
    //     var class_id = $(this).data('data-class');
    //     var home_work = $('#home_work_' + student_id+subject_id).val();
    //     var test_work = $('#test_work_' + student_id+subject_id).val();
    //     var exam = $('#exam_' + student_id+subject_id).val();

    //     $.ajax({
    //     type: "POST",
    //     url: "{{ url('admin/examinations/single_submit_marks_register') }}",
    //     data: {
    //         "_token": "{{ csrf_token() }}",
    //         student_id : student_id,
    //         subject_id : subject_id,
    //         exam_id : exam_id,
    //         class_id : class_id,
    //         home_work : home_work,
    //         test_work : test_work,
    //         exam : exam
    //     } ,
    //     dataType: "json",
    //     headers: {
    //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //     },
    //     success: function(data) {
    //         alert(data.message); // See if successful message returns
    //     },
    //     error: function(xhr, status, error) {
    //         console.error("Error: " + error);  
    //         console.log(xhr.responseText);
    //     }
    // });

    // });
// Save single subject mark when user clicks on Save button
$('.SaveSingleSubject').click(function(e){
    var student_id = $(this).attr('id');
    var subject_id = $(this).data('val'); 
    var exam_id = $(this).data('exam');  
    var class_id = $(this).data('class'); 
    var id = $(this).data('schedule');

    
    
    // Make sure to trim or sanitize IDs as needed
    var home_work = $('#home_work_' + student_id + '_' + subject_id).val();
    var test_work = $('#test_work_' + student_id + '_' + subject_id).val();
    var exam = $('#exam_' + student_id + '_' + subject_id).val();


    $.ajax({
        type: "POST",
        url: "{{ url('admin/examinations/single_submit_marks_register') }}",
        data: {
            "_token": "{{ csrf_token() }}",
            id:id,
            student_id: student_id,
            subject_id: subject_id,
            exam_id: exam_id,
            class_id: class_id,
            home_work: home_work,
            test_work: test_work,
            exam: exam
        },
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(data) {
            alert(data.message); // See if successful message returns
        },
        error: function(xhr, status, error) {
            console.error("Error: " + error);  
            console.log(xhr.responseText);
        }
    });
});


</script>
@endsection
