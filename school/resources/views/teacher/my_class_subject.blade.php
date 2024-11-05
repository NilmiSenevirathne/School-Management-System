@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1> My Class & Subject </h1>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        @include('_message')
        
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">My Class & Subject</h3>
          </div>
          
          <div class="card-body p-0">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Class Name</th>
                  <th>Subject Name</th>
                  <th>Subject Type</th>
                  <th>Created Date</th>
                </tr>
              </thead>
              <tbody>
                @if (count($getRecord) === 0)
                  <tr>
                    <td colspan="4" class="text-center">No records available for your assigned classes and subjects.</td>
                  </tr>
                @else
                  @foreach ($getRecord as $value)
                    <tr>
                      <td>{{ $value->class_name }}</td>
                      <td>{{ $value->subject_name }}</td>
                      <td>{{ $value->subject_type }}</td>
                      <td>{{ date('d-m-Y H:i A', strtotime($value->created_at)) }}</td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
</div>
@endsection
