@extends('layouts.app')

@section('content')
 

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Change Password</h1>
          </div>
          
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">

          <div class="col-md-12">
            @include('_message')
            <div class="card card-primary">
             
              <form method="POST" action ="">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="form-group">
                        <label >Old Password</label>
                        <input type="password" class="form-control"  name ="old_password" required placeholder="Enter Old Password">
                      </div>

                      <div class="form-group">
                        <label > New Password</label>
                        <input type="password" class="form-control"  name ="new_password" required placeholder="Enter New Password">
                      </div>

                </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Update</button>
                </div>
              </form>
            </div>
         

          </div>
        
        </div>
       
      </div>
    </section>
  
  </div>

@endsection