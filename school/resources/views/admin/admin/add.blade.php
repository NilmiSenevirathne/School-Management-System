@extends('layouts.app')

@section('content')
 

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add New Admin</h1>
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
            <div class="card card-primary">
             
              <!-- /.card-header -->
              <!-- form start -->
              <form method="POST" action ="">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="form-group">
                        <label >Name</label>
                        <input type="text" class="form-control" value ="{{ old('name') }}" name ="name" required placeholder="Enter name">
                        <div style ="color:red"> {{ $errors->first('name') }}</div>

                      </div>
                      <div class="form-group">
                        <label >Last_Name</label>
                        <input type="text" class="form-control" value ="{{ old('last_name') }}" name ="last_name" required placeholder="Enter last name">
                        <div style ="color:red"> {{ $errors->first('last_name') }}</div>

                      </div>

                      <div class="form-group">
                        <label >Address</label>
                        <input type="text" class="form-control"  name ="address" required placeholder="Enter address">
                        <div style ="color:red"> {{ $errors->first('address') }}</div>

                      </div>

                      <div class="form-group">
                        <label >Contact</label>
                        <input type="text" class="form-control"  name ="contact" required placeholder="Enter contact">
                        <div style ="color:red"> {{ $errors->first('contact') }}</div>

                      </div>
                  <div class="form-group">
                    <label>Email address</label>
                    <input type="email" class="form-control" value ="{{ old('email') }}"  name ="email"  required placeholder="Enter Email">
                   <div style ="color:red"> {{ $errors->first('email') }}</div>
                  </div>
                  <div class="form-group">
                    <label >Password</label>
                    <input type="password" class="form-control" name ="password" required  placeholder="Password">
                  </div>
                 
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
         

          </div>
        
        </div>
       
      </div>
    </section>
  
  </div>

@endsection