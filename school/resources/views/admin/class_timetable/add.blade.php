@extends('layouts.app')

@section('content')
 

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1> Class Timetable List </h1>
          </div>
          <div class="col-sm-6" style ="text-align:right;">

          </div>
          
        </div>
      </div>
    </section>

    <section class ="content">
    <div class="row">
     
      <div class="col-md-12">
       
        <div class="card ">
          <div class="card-header">
            <h3 class="card-title">Search Class Timetable</h3>
          </div>
        
          <form method="get" action ="">
          
            <div class="card-body">
              <div class ="row">
                <div class="form-group col-md-3">
                    <label >Class Name</label>
                    <input type="text" class="form-control" value ="{{ Request::get('class_name') }}" name ="class_name" placeholder="Enter class name">
                  </div>
                
                  <div class="form-group col-md-3">
                    <label >Subject Name</label>
                    <input type="text" class="form-control" value ="{{ Request::get('subject_name') }}" name ="subject_name" placeholder="Enter subject name">
                  </div>  
            
             
              <div class="form-group col-md-3">
                <button class = "btn btn-primary" type = "submit" style = "margin-top:30px;">Search</button>
                <a href ="{{ url('admin/class_timetable/list') }}" class = "btn btn-success" style ="margin-top:30px;">Reset</a>
              </div>
            </div>
             
            </div>
         
          </form>
        </div>
     

      </div>
    
    </div>
  </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

           

            
              
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
     
    </section>
  

@endsection