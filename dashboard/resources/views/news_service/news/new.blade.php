@extends('adminlte::page')

@section('title', 'Manager')

@section('content_header')
    <h1>Managers</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- jquery validation -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Manager Detail</small></h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form method="POST" id="manager_form" action="{{route('news.store')}}" role="form" novalidate="novalidate" >
            {{ csrf_field() }}

            <div class="card-body">
              <div class="form-group">
                <label for="title">Manager Name</label>
                <input  type="text" name="name" class="form-control" id="name" placeholder="Enter manager name">
              </div>
              <div class="form-group">
                <label for="email">Manager Email</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="Enter manager email">
              </div>
              <div class="form-group">
                <label for="password">Manager Password</label>
                <input  type="password" name="password" class="form-control" id="password" placeholder="Enter manager password">
              </div>

              <div class="form-group mb-0">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" name="is_active" class="custom-control-input" id="is_active"  value="1" checked >
                  <label class="custom-control-label" for="exampleCheck1">Active</label>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Submit</button>
              <a href="{{route('news.index')}}" class="btn btn-default">Back</a>
            </div>
          </form>
        </div>
        <!-- /.card -->
        </div>
      <!--/.col (left) -->
      <!-- right column -->
      <div class="col-md-6">

      </div>
      <!--/.col (right) -->
    </div>
    <!-- /.row -->
  </div>

@stop

@section('css')

@stop
@section('plugins.Datatables', true)
@section('js')
<script>


$(document).ready(function () {

  $.validator.setDefaults({

    submitHandler: function (form) {
      //console.log('ok');
      form.submit();
    }
  });
  $('#manager_form').validate({
    rules: {
      title: {
        required: true,
      },
    },
    messages: {
      title: {
        required: "Please enter a manager's name",
      },


    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
});

</script>
@stop
