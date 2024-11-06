@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Timetable</h1>
                </div>
            </div>
        </div>
    </section>

    @include('_message')

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @foreach($week as $value)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $value['week_name'] }}</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Room Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($value['timetable'] as $timetable)
                                    <tr>
                                        <td>{{ $timetable['subject_name'] }}</td>
                                        <td>{{ $timetable['start_time'] }}</td>
                                        <td>{{ $timetable['end_time'] }}</td>
                                        <td>{{ $timetable['room_number'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No timetable assigned for this week.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
