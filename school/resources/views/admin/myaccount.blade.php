@extends('layouts.app')

@section('content')
 

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>My Account</h1>
          </div>
          
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            @include('_message')

            <div class="card card-primary">
             
              <!-- /.card-header -->
              <!-- form start -->
              <form method="POST" action ="">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="form-group">
                        <label >Name</label>
                        <input type="text" class="form-control" name ="name" value ="{{old('name',$getRecord->name )}}"  required placeholder="Enter name">
                      </div>
                      <div class="form-group">
                        <label >Last Name</label>
                        <input type="text" class="form-control" name ="last_name" value ="{{old('last_name',$getRecord->last_name )}}"  required placeholder="Enter last name">
                      </div>
                      <div class="form-group">
                        <label >Address</label>
                        <input type="text" class="form-control" name ="address" value ="{{old('address',$getRecord->address )}}"  required placeholder="Enter Adress">
                      </div>
                      <div class="form-group">
                        <label >Contact</label>
                        <input type="text" class="form-control" name ="contact" value ="{{old('contact',$getRecord->contact )}}"  required placeholder="Enter Contact">
                      </div>
                      <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $getRecord->email) }}" required placeholder="Enter Email">
                        @if ($errors->has('email'))
                            <div class="text-danger">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <!-- Hidden Old Email Field -->
                    <input type="hidden" name="old_email" value="{{ $getRecord->email }}">
               
                 
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Update</button>
                </div>
              </form>
            </div>
         

          </div>
        
        </div>
       
      </div><
    </section>
  
  </div>

@endsection