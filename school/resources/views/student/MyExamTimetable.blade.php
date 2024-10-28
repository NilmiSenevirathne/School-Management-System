@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Exam Timetable</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    @include('_message')

                    @foreach($getRecord as $value)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $value['exam_name'] }}</h3> <!-- Change from 'name' to 'exam_name' -->
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Subject Name</th> <!-- Changed column name for clarity -->
                                        <th>Day</th>
                                        <th>Exam Date</th> <!-- Changed column name for clarity -->
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Location</th>
                                        <th>Full Marks</th> 
                                        <th>Passing Marks</th> <!-- Added passing mark column -->
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($value['exam'] as $valueS)
                                    <tr>
                                        <td>{{ $valueS['subject_name'] }}</td>
                                        <td>{{ date('l',strtotime($valueS['exam_date'])) }}</td>
                                        <td>{{ date('d-m-Y',strtotime($valueS['exam_date'])) }}</td>
                                        <td>{{ date('h:i A',strtotime($valueS['start_time'])) }}</td>
                                        <td>{{ date('h:i A',strtotime($valueS['end_time'])) }}</td>
                                        <td>{{ $valueS['location'] }}</td>
                                        <td>{{ $valueS['full_marks'] ?? 'N/A' }}</td> <!-- Show 'N/A' if passing mark is null -->
                                        <td>{{ $valueS['passing_marks'] ?? 'N/A' }}</td> <!-- Show 'N/A' if passing mark is null -->

                                    </tr>
                                    @endforeach <!-- Ensure you close the foreach loop -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach <!-- Ensure you close the outer foreach loop -->
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
