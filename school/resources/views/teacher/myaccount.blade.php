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
            @include('_message')
            <!-- general form elements -->
            <div class="card card-primary">
             
              <!-- /.card-header -->
              <!-- form start -->
              <form method="POST" action =""enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class = "row">
                        <div class="form-group col-md-6">
                            <label >First Name</label>
                            <input type="text" class="form-control" value ="{{ old('name',$getRecord->name) }}" name ="name" required placeholder="Enter name">
                            <div style ="color:red"> {{ $errors->first('name') }}</div>

                          </div>
                          <div class="form-group col-md-6">
                            <label >Last Name</label>
                            <input type="text" class="form-control" value ="{{ old('last_name',$getRecord->last_name) }}" name ="last_name" required placeholder="Enter last name">
                            <div style ="color:red"> {{ $errors->first('last_name') }}</div>

                          </div>
                          <div class="form-group col-md-6">
                            <label >Address</label>
                            <input type="text" class="form-control" value ="{{ old('address',$getRecord->address) }}" name ="address" required placeholder="Enter address">
                            <div style ="color:red"> {{ $errors->first('address') }}</div>

                          </div>

                          
                          <div class="form-group col-md-6">
                            <label >Gender</label>
                            <select class ="form-control" required name ="gender">
                                <option value ="">Select Gender</option>
                                <option {{ (old('gender',$getRecord->gender) == 'Male') ? 'selected': '' }} value ="Male">Male</option>
                                <option {{ (old('gender',$getRecord->gender) == 'Female') ? 'selected': '' }} value ="Female">Female</option>
                            </select>
                            <div style ="color:red"> {{ $errors->first('gender') }}</div>

                          </div>
                          <div class="form-group col-md-6">
                            <label >Date of Birth</label>
                            <input type="date" class="form-control" value ="{{ old('date_of_birth',$getRecord->date_of_birth) }}" name ="date_of_birth" required >
                            <div style ="color:red"> {{ $errors->first('date_of_birth') }}</div>

                          </div>
                        
                          <div class="form-group col-md-6">
                            <label >Contact</label>
                            <input type="text" class="form-control" value ="{{ old('contact',$getRecord->contact) }}" name ="contact"  placeholder="Enter contact">
                            <div style ="color:red"> {{ $errors->first('contact') }}</div>

                          </div>
                          <div class="form-group col-md-6">
                            <label >Qualification</label>
                            <textarea class="form-control" name ="qualification" placeholder="Enter qualification">{{ old('qualification',$getRecord->qualification) }}</textarea>
                            <div style ="color:red"> {{ $errors->first('qualification') }}</div>

                          </div>
                          <div class="form-group col-md-6">
                            <label >Experience</label>
                            <textarea class="form-control" name ="experience" placeholder="Enter experience">{{ old('experience',$getRecord->experience) }}</textarea>
                            <div style ="color:red"> {{ $errors->first('experience') }}</div>

                          </div>
                        
                          <div class="form-group col-md-6">
                            <label >Profile</label>
                            <input type="file" class="form-control"  name ="profile_picture" >
                            <div style ="color:red"> {{ $errors->first('profile_picture') }}</div>
                            @if(!empty($getRecord->getTeacherProfile()))
                            <img src = "{{ $getRecord->getTeacherProfile() }}" style = "width:100px;">
                            @endif

                          </div>
                         
                          
                          <div class="form-group col-md-6">
                            <label>Email address</label>
                            <input type="email" class="form-control" value ="{{ old('email',$getRecord->email) }}"  name ="email"  required placeholder="Enter Email">
                           <div style ="color:red"> {{ $errors->first('email') }}</div>
                          </div>
                          <!-- Hidden Old Email Field -->
                          <input type="hidden" name="old_email" value="{{ $getRecord->email }}">
                          

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