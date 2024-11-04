@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>My Timetable </h1>
          </div>
        </div>
      </div>
    </section>
    @include('_message')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
              
               
              <div class="card">
              <div class="card-header">
                <h3 class="card-title">My Timetable</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <table class="table table-striped">
                  <thead>
                       <tr>
                          <th>Week</th>
                          <th>Start Time</th>
                          <th>End Time</th>
                          <th>Room Number</th>
                       </tr>
                  </thead>
                  <tbody>
                   
                </tbody>
                </table>

                <div style ="padding:10px; float:right;">
                
              </div>
              </div>
            
          

            </div>
        </div>
    </section>
</div>

<!-- AJAX Script for fetching subjects based on selected class -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.getClass').change(function() {
            var class_id = $(this).val();
            
            if (class_id) {
                $.ajax({
                    url: "{{ url('admin/class_timetable/get_subject') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "class_id": class_id
                    },
                    dataType: "json",
                    success: function(response) {
                        $('.getSubject').empty(); // Clear current options
                        $('.getSubject').append('<option value="">Select</option>');
                        
                        // Populate the subject dropdown with new options
                        $.each(response, function(key, subject) {
                            $('.getSubject').append('<option value="' + subject.id + '">' + subject.name + '</option>');
                        });
                    }
                });
            }
        });
    });
</script>
@endsection
