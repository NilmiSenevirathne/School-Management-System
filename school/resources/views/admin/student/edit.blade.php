@extends('layouts.app')

@section('content')
 

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Student</h1>
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
                            <label >Last_Name</label>
                            <input type="text" class="form-control" value ="{{ old('last_name',$getRecord->last_name) }}" name ="last_name" required placeholder="Enter last name">
                            <div style ="color:red"> {{ $errors->first('last_name') }}</div>

                          </div>
                          <div class="form-group col-md-6">
                            <label >Address</label>
                            <input type="text" class="form-control" value ="{{ old('address',$getRecord->address) }}" name ="address" required placeholder="Enter address">
                            <div style ="color:red"> {{ $errors->first('address') }}</div>

                          </div>

                          <div class="form-group col-md-6">
                            <label >Admission Number</label>
                            <input type="text" class="form-control" value ="{{ old('admission_number',$getRecord->admission_number) }}" name ="admission_number" required placeholder="Enter admission number">
                            <div style ="color:red"> {{ $errors->first('admission_number') }}</div>

                          </div>
                          <div class="form-group col-md-6">
                            <label >Roll Number</label>
                            <input type="text" class="form-control" value ="{{ old('roll_number',$getRecord->roll_number) }}" name ="roll_number" required placeholder="Enter roll number">
                            <div style ="color:red"> {{ $errors->first('roll_number') }}</div>

                          </div>
                          <div class="form-group col-md-6">
                            <label >Class </label>
                            <select class ="form-control" required name ="class_id">
                                <option value =" ">Select Class</option>
                                @foreach ($getClass as $value )
                                <option {{ (old('class_id',$getRecord->class_id) == $value->id) ? 'selected': '' }} value="{{$value->id }}">{{ $value->name }}</option>
                                  
                                @endforeach

                            </select>
                            <div style ="color:red"> {{ $errors->first('class_id') }}</div>

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
                            <label >Admission Date</label>
                            <input type="date" class="form-control" value ="{{ old('admission_date',$getRecord->admission_date) }}" name ="admission_date" required >
                            <div style ="color:red"> {{ $errors->first('admission_date') }}</div>

                          </div>
                          <div class="form-group col-md-6">
                            <label >Profile</label>
                            <input type="file" class="form-control"  name ="profile_picture" >
                            <div style ="color:red"> {{ $errors->first('profile_picture') }}</div>
                            @if(!empty($getRecord->getStudentProfile()))
                            <img src = "{{ $getRecord->getStudentProfile() }}" style = "width:100px;">
                            @endif

                          </div>
                          <div class="form-group col-md-6">
                            <label >Status</label>
                            <select class = "form-control" name="status">
                                <option value = "">Select Status</option>
                                <option {{ (old('status',$getRecord->status) == 0) ? 'selected': '' }}  value = "0">Active</option>
                                <option {{ (old('status',$getRecord->status) == 1) ? 'selected': '' }} value = "1">Inactive</option>
                            </select>
                            <div style ="color:red"> {{ $errors->first('status') }}</div>

                          </div>
                          
                          <div class="form-group col-md-6">
                            <label>Email address</label>
                            <input type="email" class="form-control" value ="{{ old('email',$getRecord->email) }}"  name ="email"  required placeholder="Enter Email">
                           <div style ="color:red"> {{ $errors->first('email') }}</div>
                          </div>
                           <!-- Hidden Old Email Field -->
                              <input type="hidden" name="old_email" value="{{ $getRecord->email }}">

                          <div class="form-group col-md-6">
                            <label >Password</label>
                            <input type="text" class="form-control" name ="password"   placeholder="Password">
                            <p>Do you want change password , Please add new password</p>

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